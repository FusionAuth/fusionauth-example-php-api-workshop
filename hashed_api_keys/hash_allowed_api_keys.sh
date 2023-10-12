#!/bin/sh

cp /dev/null allowed_api_keys_hashed.txt

for i in `cat ../api_keys/allowed_api_keys.txt `; do                
  /bin/echo -n "$i" | sha256sum |awk '{print $1}' >> allowed_api_keys_hashed.txt
done


