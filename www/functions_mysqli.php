<?php
  global $cnx;

  function sqlconnect()
  {
    global $cnx;
    $cnx = new mysqli("localhost", "padx", "Jgw9uhSRbM5vaM8e", "padx");
  }

  function sqldisconnect()
  {
    global $cnx;
    $cnx->close();
  }

  function sqlexec($sql)
  {
    global $cnx;
    if ($rs = $cnx->query($sql))
    {
    }
    else
    {
      $error = $cnx->error;
      print $error;
    }
    return $rs;
  }

  function sqlrow($result)
  {
    @$row = mysqli_fetch_array($result, MYSQLI_BOTH);
    return $row;
  }

  function sqlnum($result)
  {
    return mysqli_num_rows($result);
  }

  function sqlone($sql)
  {
    $row = sqlrow(sqlexec($sql));
    return @$row[0];
  }

  function sqltwo($sql)
  {
    $row = sqlrow(sqlexec($sql));
    return array(@$row[0], @$row[1]);
  }

  function sqlthree($sql)
  {
    $row = sqlrow(sqlexec($sql));
    return array(@$row[0], @$row[1], @$row[2]);
  }

  function sqlfour($sql)
  {
    $row = sqlrow(sqlexec($sql));
    return array(@$row[0], @$row[1], @$row[2], @$row[3]);
  }

  function sqlfive($sql)
  {
    $row = sqlrow(sqlexec($sql));
    return array(@$row[0], @$row[1], @$row[2], @$row[3], @$row[4]);
  }

  function sqlsix($sql)
  {
    $row = sqlrow(sqlexec($sql));
    return array(@$row[0], @$row[1], @$row[2], @$row[3], @$row[4], @$row[5]);
  }

  function sqlseven($sql)
  {
    $row = sqlrow(sqlexec($sql));
    return array(@$row[0], @$row[1], @$row[2], @$row[3], @$row[4], @$row[5], @$row[6]);
  }

  function sqlid()
  {
    global $cnx;
    return mysqli_insert_id($cnx);
  }
?>