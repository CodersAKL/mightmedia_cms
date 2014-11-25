#!/bin/sh

# the final changelog file
OUTPUT_FILE=CHANGELOG.md

# generate the changelogs
git --no-pager log --no-merges --format="%ai %aN %n%n%x09* %s%d%n" > $OUTPUT_FILE

# prevent recursion!
# since a 'commit --amend' will trigger the post-commit script again
# we have to check if the changelog file has changed or not
res=$(git status --porcelain | grep $OUTPUT_FILE | wc -l)
if [ "$res" -gt 0 ]; then
  git add $OUTPUT_FILE
  git commit --amend
  echo "Populated Changelog in $OUTPUT_FILE"
fi

# generate version
git rev-list --count HEAD > version.txt