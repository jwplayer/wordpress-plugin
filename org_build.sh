#!/bin/bash

echo "Building Wordpress.org version of the plugin"

mkdir -p build;
cp -r jw-player build/;

PLUGIN_VERSION=$(awk '/Version: /{print $2}' "build/jw-player/jw-player.php");

# Remove older zip of the same version
if [ -f "build/jw-player_v$PLUGIN_VERSION.zip" ]
  then
  echo "Removing older zip of the same version";
  rm "build/jw-player_v$PLUGIN_VERSION.zip";
fi

cd build;
zip -mqr "jw-player_v$PLUGIN_VERSION.zip" jw-player -x *.svn* -x *.DS_Store;
cd ../;


# Remove jw player directory
echo "Removing temporary directory";
rm -rf "build/jw-player";

echo "Done.";
