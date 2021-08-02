mysqldump  pdc >pdc.sql

tnames='icd outward_to search_path user_group xml xml_template'

#####if root password
#####mysqldump  -uroot cl_general $tnames -p$password > "cl_general_data.sql"
#####if unix plugin , as root 
mysqldump  cl_general $tnames > "xml_data.sql"


git add *
git commit -a
git push https://github.com/nishishailesh/xml master

