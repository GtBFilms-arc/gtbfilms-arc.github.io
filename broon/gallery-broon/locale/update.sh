#!/bin/bash

# - YaPiG - Yet Another PHP Image Gallery 
# 
# This Script Updates PO files of each language
# adding new msgids and keeping already translated msgids
#

#This are all languages
ALL_LANGS="br ca cn cz de es fi fr gl hu id it ja  nl no pl ro ru sv sk"

echo "YaPiG: Obtaining yapig.pot (Translate File Template)"
xgettext --output=yapig.pot --default-domain=yapig -L php  --add-comments --keyword=_y  --no-wrap --width=1024 --files-from=po.in

#Now update each language files
for LANG in $ALL_LANGS
do 
    cd $LANG/LC_MESSAGES/
    msgmerge --width=1024 --no-wrap -o yapig-latest.po yapig.po ../../yapig.pot
    cd -
    echo "--> Updated language $LANG";
done
echo "Finished updating all supported Languages"
