<?php 
class Database 
{

  static function execOnDb($query)
  {
            TTransaction::open('padrao'); // abre uma transação 
            $conn = TTransaction::get(); // obtém a conexão 
            
            $sth = $conn->prepare($query); 
            
            $sth->execute();
            $result = $sth->fetchAll();
			TTransaction::close();
            if(isset($result[0][0]))
            {
              return $result[0][0];
            }
            
  }
  static function execOrZero($query)
  {
            TTransaction::open('padrao'); // abre uma transação 
            $conn = TTransaction::get(); // obtém a conexão 
            
            $sth = $conn->prepare($query); 
            
            $sth->execute();
            $result = $sth->fetchAll();
		       	TTransaction::close();
            if(isset($result[0][0]))
            {
              return $result[0][0];
            }else{
              return 0;
            }
            
  }
  
   static function executa($query)
  {
            TTransaction::open(TSession::getValue('banco_id')); // abre uma transação 
            $conn = TTransaction::get(); // obtém a conexão 
            
            $sth = $conn->prepare($query); 
            
            $sth->execute();
            $result = $sth->fetchAll();
			TTransaction::close();
            return $result;
            
  }
  
   static function updateOnDb($query)
  {
            TTransaction::open('padrao'); // abre uma transação 
            $conn = TTransaction::get(); // obtém a conexão 
            
            $sth = $conn->prepare($query); 
            $sth->execute();
            
            TTransaction::close();
  }
  function query($sql)
  {
	  
            TTransaction::open('padrao'); // abre uma transação 
			$conn = TTransaction::get(); // get PDO connection
            
            // run query
            $result = $conn->query($sql);
			TTransaction::close();
  }
static function consulta($query)

  {

            TTransaction::open('padrao'); // abre uma transação 

            $conn = TTransaction::get(); // obtém a conexão 

            

            $sth = $conn->prepare($query); 

            

            $sth->execute();

            $result = $sth->fetchAll();

			TTransaction::close();

            if(isset($result))

            {
              return $result;
			}
            

            

  }

}

?>
