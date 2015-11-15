-- add table prefix if you have one
DELETE FROM core_config_data WHERE path like 'ffuenf_common/%';
DELETE FROM core_resource WHERE code = 'ffuenf_common_setup';
DELETE FROM core_config_data WHERE path = 'advanced/modules_disable_output/Ffuenf_Common';