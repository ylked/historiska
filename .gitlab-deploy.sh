#!/bin/bash

cd ..

tar czf historiska.tar.gz historiska/*
scp historiska.tar.gz ubuntu@historiska.ch:.
ssh ubuntu@historiska.ch './startup.sh' 
