#!/bin/bash
PID="hosting.pid"
if [ -f "$PID" ]; then
    echo "$PID exist"
    exit 0
else   
	dt=`date '+%d/%m/%Y %H:%M:%S'`
    	echo "${dt}" > ${PID} 
    	whoami       
	KEY=$2
	OWNER=$1
	HOST="database-main.cycq0z9urb9l.eu-west-2.rds.amazonaws.com"
	#echo "params $KEY - $OWNER"
	PASSWD=$(grep password conf.php | cut -d = -f 2 | cut -d \" -f 2 | grep -v "conn*") #tohle je v hajzlu (mozna to -e u echa)
	USER=$(grep username conf.php | cut -d = -f 2 | cut -d \" -f 2 | grep -v "conn*")
	#PASSWD="Kockopes01"
	# -e "SELECT JSON_ARRAYAGG(JSON_OBJECT(*)) FROM users_requests.request_by_user WHERE done = 'waiting' AND request_string = 'smzOeMSC7JTDaA==' LIMIT 1;
	#git clone https://github.com/GaldCZ/hosting.git
	#echo "git"
	#git -C hosting/ pull

	CONN="mysql -h ${HOST} -P 3307 -u ${USER} -p${PASSWD}"
	#echo $CONN
	echo $PASSWD
	echo $USER
	#echo $CONN -s -e "SELECT * FROM users_requests.request_by_user WHERE request_string = '${KEY}' AND user_id = '${OWNER}' AND done = 'waiting' AND request_type = 'create' LIMIT 1;" | csvcut -t
	ROW=$($CONN -s -e "SELECT * FROM users_requests.request_by_user WHERE request_string = '${KEY}' AND user_id = '${OWNER}' AND done = 'waiting' AND request_type = 'create' LIMIT 1;" | csvcut -t) 
	echo $?
	echo $ROW
	if [ -z "${ROW}" ];then
      		echo "No MySQL data"
      		rm ${PID}
      		exit 1
      		
	fi

	echo "$ROW"

	IMAGE=$(echo ${ROW} | awk -F "," {'print $9'}) #ok
	NAME=$(echo ${ROW} | awk -F "," {'print $5'})
	SIZE=$(echo ${ROW} | awk -F "," {'print $8'})  #ok
	REGION=$(echo ${ROW} | awk -F "," {'print $7'})
	#OWNER=$(echo ${ROW} | awk -F "," {'print $2'})
	REQUEST=$(echo ${ROW} | awk -F "," {'print $1'})
	echo "aws ec2 run-instances --image-id ${IMAGE} --count 1 --instance-type ${SIZE} --key-name id_rsa --security-group-ids user --user-data file:///home/ubuntu/download/hosting/build.sh --region ${REGION} --tag-specifications 'ResourceType=instance,Tags=[{Key=Request,Value=${REQUEST}},{Key=Name,Value=client${OWNER}}]'"
	RESULT=$(aws ec2 run-instances --image-id ${IMAGE} --count 1 --instance-type ${SIZE} --key-name id_rsa --security-group-ids user --user-data file:///home/ubuntu/download/hosting/build.sh --region ${REGION} --tag-specifications "ResourceType=instance,Tags=[{Key=Request,Value=${REQUEST}},{Key=Name,Value=client${OWNER}}]")
	echo ?$
    echo $RESULT
    INSTANCE_ID=$(echo $RESULT | jq .Instances[].InstanceId | sed 's/"//g')  # sed od quotas           An error occurred (InvalidInstanceID.Malformed) when calling the DescribeInstanceStatus operation: Invalid id: ""i-09f3216951334bb91""
    
    echo "instance : ${INSTANCE_ID}"
    
    if [ -z "${INSTANCE_ID}" ];then
      		echo "No instance data"
      		exit 1
    fi

	#Podminka na zpetnou varbu  aws ec2 describe-instance-status --region eu-west-2 --instance-id i-08bb85c34cd782ca3 | jq .InstanceStatuses[].InstanceState.Name
    echo "aws ec2 describe-instance-status --region ${REGION} --instance-id $INSTANCE_ID | jq .InstanceStatuses[].InstanceState.Name"
    
    # if [ -z "${STATUS}" ];then
    #             echo "No status data"
    #             exit 1
    # fi


    while [ "$(aws ec2 describe-instance-status --region "${REGION}" --instance-id "${INSTANCE_ID}" | jq .InstanceStatuses[].InstanceState.Name | sed 's/\"//g')" != "running" ];do
    sleep 2
     ((i++))
     echo $i
	if [[ "$i" == '35' ]]; then  		
		echo "not running"
		break
		exit 1
  	fi	   
    done #tohle je mozna zbytecny
    echo "do na ip" 
    PUBLIC_IP=$(aws ec2 describe-instances --region "${REGION}" --instance-id "${INSTANCE_ID}" | jq .Reservations[].Instances[].PublicIpAddress | sed 's/"//g')
	###FINISH###
	${CONN} -e "UPDATE users_requests.request_by_user SET done='done' WHERE request_type = 'create' AND request_string = '${KEY}' AND r_id = '$REQUEST' LIMIT 1;"
    ${CONN} -e "INSERT INTO users_requests.users_compute (id, user_id, instance_id, instance_region, instance_ip, instance_size) VALUES ('', '${OWNER}', '${INSTANCE_ID}', '${REGION}', '${PUBLIC_IP}', '${SIZE}');"
    echo $?
	###ZAVOLANI SIGN NA PUPPET
	###POVOLANI NOVE TABULKY VPS > vytahnout do ni info
    

### MAKE SIGN IN MASTER   mysql -h database-main.cycq0z9urb9l.eu-west-2.rds.amazonaws.com -P 3307 -u admin -pKockopes01 -s -N -e "SELECT * FROM users_requests.request_by_user WHERE done = 'waiting' AND request_string = 'yDaHz52pKCPT6Q==' AND request_type = 'create' LIMIT 1;"
	rm ${PID}
fi
