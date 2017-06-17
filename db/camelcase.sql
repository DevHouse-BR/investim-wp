update investim.investimwp_terms set name = CamelCase(name) where 1;
use investim;

 DELIMITER |
       CREATE FUNCTION CamelCase(str VARCHAR(8000))
       RETURNS VARCHAR(8000) 
          BEGIN
            DECLARE result VARCHAR(8000);
            SET str = CONCAT(' ',str,' ');
            SET result = '';
            WHILE LENGTH(str) > 1 DO
               SET str = SUBSTR(str,INSTR(str,' ')+1,LENGTH(str));
               SET result = CONCAT(result,UPPER(LEFT(str,1)), LOWER(SUBSTR(str,2,INSTR(str,' ') - 1)) )  ;
               SET str = SUBSTR(str,INSTR(str,' '),LENGTH(str));  
           END WHILE;
        RETURN result;
      END 
     |
     DELIMITER ;