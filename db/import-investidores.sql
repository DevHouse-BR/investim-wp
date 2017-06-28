LOAD DATA INFILE 'D:\\investidores.csv' INTO TABLE investim.investimwp_wpcasama_mailalert
	CHARACTER SET UTF8
	FIELDS TERMINATED BY ',' 
	OPTIONALLY ENCLOSED BY '"' 
	ESCAPED BY '\\';
	-- LINES TERMINATED BY '\\n';