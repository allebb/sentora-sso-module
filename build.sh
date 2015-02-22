#!/usr/bin/env sh
echo "########################################################"
echo "# Sentora Module Compiler                              #"
echo "# (c) Supared Limited, 2015                            #"
echo "########################################################"
echo ""
echo "Checking required tools are installed..."
type zip >/dev/null 2>&1 || { echo >&2 "The package 'zip' is required but not found on this server, please install and try again."; exit 1; }
echo "Checking that a previously built module does not already exist..."
if [ -f "build/sso.zpp" ]; then
    echo "  Previously built package has been found, cleaning up now..."
    rm build/sso.zpp
    echo "  Clean up complete!"
fi
echo "Compiling package..."
cd src
zip -r ../build/sso.zpp *
cd ../
echo "Package compilation complete!"
echo "The package can be found in the build/ directory!"
echo ""
exit 0