# AdminPro Class MySQL Table Update: v1.2 to v1.3.x
#
# The AdminPro Class MySQL Table structure has changed in v1.3.x
# You can update your existing table to be compatible with v!.3.x without changing any existing data.
# Change the values of the SQL statement according to your configuration file
# 'myser':Table name; 'userGroup':User group field name (new in v1.3.x); 'isAdmin':Administrator field name;
# Backup first your AdminPro Class MySQL Table, just for any case...
# Execute the SQL statement:

ALTER TABLE `myuser` ADD `userGroup` INT( 10 ) UNSIGNED DEFAULT 1 AFTER `isAdmin` ;