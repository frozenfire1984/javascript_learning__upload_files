#!/bin/bash
echo " "
echo "\033[1;32mclearing image folder:\033[0m"
count=$(find images -maxdepth 1 -type f | wc -l)
count=${count//[[:blank:]]/}
if [[ $count == 0 ]]
then
    echo "\033[1;31mthe folder is already empty!\033[0m" 
else
    rm -rf images/*
    echo "\033[1;31mremoved ${count//[[:blank:]]/} images\033[0m" 
    exit
fi

