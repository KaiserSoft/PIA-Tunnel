#!/bin/bash
# script to test the IP list generation feature. I noticed a lot of doulbe IPs in the list and
# will use this script to validate my logic
LANG=en_US.UTF-8
export LANG
source '/usr/local/pia/settings.conf'
source '/usr/local/pia/include/functions.sh'




# generate a few IPs for the uptime functions
gen_ip_list 30

for ip in ${PING_IP_LIST[@]}
do
	echo "$ip"
	#check every IP against PING_IP_LIST to check if every entry is unique
	#is_ip_unique $ip PING_IP_LIST[@]
    #if [ "$RET_IP_UNIQUE" != "yes" ]; then
	#	remove_ip_from_array PING_IP_LIST[@] "$ip"
	#	PING_IP_LIST=("${RET_MODIFIED_ARRAY[@]}")
    #  echo "$ip NOT unique"
    #fi
done





