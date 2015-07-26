# update-website.sh (c) 2015 by Thorsten Staerk

# This will update www.staerk.de/files/mediasyntax.tar.gz
# you need to have passwordless login established to www.staerk.de

echo $(dirname $0)
cd $(dirname $0)
cd ..
cd ..
tar cvzf mediasyntax.tar.gz mediasyntax && scp mediasyntax.tar.gz www.staerk.de:/var/www/staerk.de/files
