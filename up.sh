#!/usr/bin/env bash
rm -rf _build
rm ./data-structs.tar.gz
make html
cd _build/html
tar -czvf ../../data-structs.tar.gz  ./*
cd ../..
sshpass -pkk0457 scp ./data-structs.tar.gz kurt@66.172.33.113:~/
sshpass -pkk0457 ssh kurt@66.172.33.113 
