#!/bin/bash

LINES_TO_REMOVE=7

echo "Building VIP Version of the plugin"

mkdir -p build;
cp -r jw-player build/;

# Remove import.php
if [ -f "build/jw-player/include/import.php" ]
  then
  rm "build/jw-player/include/import.php";
fi

# Remove the last lines from the jw-player.php file.
mv "build/jw-player/jw-player.php" "build/jw-player.php";
cat "build/jw-player.php" | tail -r | tail +$(($LINES_TO_REMOVE + 1)) | tail -r >> "build/jw-player/jw-player.php";
rm "build/jw-player.php";


PLUGIN_VERSION=$(awk '/Version: /{print $2}' "build/jw-player/jw-player.php");

# Remove older zip of the same version
if [ -f "build/jw-player_vip_v$PLUGIN_VERSION.zip" ]
  then
  echo "Removing older zip of the same version";
  rm "build/jw-player_vip_v$PLUGIN_VERSION.zip";
fi


cd build;
zip -mqr "jw-player_vip_v$PLUGIN_VERSION.zip" jw-player -x *.svn* -x *.DS_Store;
cd ../;

echo "Removing temporary directory";
rm -rf "build/jw-player";

# Remove jw player directory

echo "Done."