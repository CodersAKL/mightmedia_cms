#!/bin/sh
git rev-list --count HEAD | git log --pretty=oneline > ../version.txt