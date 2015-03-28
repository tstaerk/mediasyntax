<?php
// dokuwiki2mediawiki.php (c) 2010-2015 by Thorsten Staerk
// This program reads files containing dokuwiki syntax and converts them into files
// containing mediawiki syntax.
// The source file is given by parameter, the target file is the source file plus a
// ".mod" suffix.

// TODO:
// filename: not only one file name!
// test if we overwrite a file
// test if file exists
// allow rowspan (::: in dokuwiki syntax)

$in_table=false;

if ($argc==1)
{
  echo "dokuwiki2mediawiki.php (c) 2010-2012 by Thorsten Staerk\n";
  echo "This program converts dokuwiki syntax to mediawiki syntax.\n";
  echo "The source file is given as an argument, the target file is the same plus the suffix \".mod\"\n";
  echo "Usage: php dokuwiki2mediawiki <file>\n";
  echo "Example: php dokuwiki2mediawiki start.txt\n";
}
else
for ($argument=1;$argument<$argc;$argument++)
{
  $filename=$argv[$argument];
  $inputfile=fopen($filename,"r");
  $i=0;
  $output="";
  if ($inputfile) 
  {
    while (!feof($inputfile))
    {
      $lines[$i++]=fgets($inputfile); //we start counting a 0
    }
    fclose($inputfile);
  }
  $linecount=$i;
  $i=-1;

  while (++$i<$linecount)
  {
    if ($in_table) $row++;
    // replace headings
    $line=$lines[$i];
    if (preg_match('/^ *======.*====== *$/',$line))
    {
      $line=preg_replace('/^ *======/','=',$line);
      $line=preg_replace('/====== *$/','=',$line);
    }
    elseif (preg_match('/^ *=====.*===== *$/',$line))
    {
      $line=preg_replace('/^ *=====/','==',$line);
      $line=preg_replace('/===== *$/','==',$line);
    }
    elseif (preg_match('/^ *====.*==== *$/',$line))
    {
      $line=preg_replace('/^ *====/','===',$line);
      $line=preg_replace('/==== *$/','===',$line);
    }
    elseif (preg_match('/^ *===.*=== *$/',$line))
    {
      $line=preg_replace('/^ *===/','====',$line);
      $line=preg_replace('/=== *$/','====',$line);
    }
    elseif (preg_match('/^ *==.*== *$/',$line))
    {
      $line=preg_replace('/^ *==/','=====',$line);
      $line=preg_replace('/== *$/','=====',$line);
    }
    // end of replace headings

    // replace bulletpoints
    $level=0; // level of bulletpoints, e.g. * is level 1, *** is level 3.
    while (preg_match('/^(  )+\*/',$line))
    {
      $line=preg_replace("/^  /","",$line);
      $level++;
    }
    while ($level>1)
    {
      $line="*".$line;
      $level--;
    }
    // end of replace bulletpoints

    // replace ordered list items
    $level=0; // level of list items, e.g. - is level 1, --- is level 3.
    while (preg_match('/^(  )+\-/',$line))
    {
      $line=preg_replace("/^  /","",$line);
      $level++;
      $line=preg_replace("/^-/","#",$line);
    }

    while ($level>1)
    {
      $line="#".$line;
      $level--;
    }
    // end of replace ordered list items

    // replace //
    $line=preg_replace("/\/\//","''",$line);
    // end of replace //

    // replace **
    $line=preg_replace("/\*\*/","'''",$line);
    // end of replace **
    
    // replace \\
    // thanks to Rakete Kalle
    $line=preg_replace("/\\\\\\\\ /","<br />",$line);
    // end of replace \\

    // begin care for tables
    if (preg_match("/^\|/",$line))
    {
      $line=preg_replace("/\| *$/","",$line);
      $line=preg_replace("/\n/","",$line);
      if (!$in_table)
      {
        $in_table=true;
        $row=1;
      }
      $cells[$row]=explode("|",preg_replace("/^\|/","",$line));
    }

    // have we left a table?
    if ((!preg_match("/^\|/",$line)) && $in_table)
    {
      $in_table=false;
      if ($headers!="")
      for ($n=0;$n<count($headers);$n=$n+1)
      {
        echo $headers[$n]; echo "|";
      }
      echo "\n";
      for ($y=1;$y<count($cells)+1;$y=$y+1)
      {
        for ($x=0;$x<count($cells[$y]);$x=$x+1)
        {
          echo $cells[$y][$x]; echo "|";
        }
        echo "\n";
      }
      echo "***** END *****\n";
      $rowspancells=$cells;

      // each cell's rowspan value is 1
      for ($y=1;$y<count($cells)+1;$y=$y+1)
        for ($x=0;$x<count($cells[$y]);$x=$x+1)
          $rowspancells[$y][$x]=1;

      // every cell that needs an attribute rowspan=x gets x as its rowspan value
      for ($y=1;$y<count($cells);$y=$y+1)
      {
        for ($x=0;$x<count($cells[$y]);$x=$x+1)
        {
          $z=1;
          while (($y+$z<=count($cells)) && (preg_match("/ *::: */",$cells[$y+$z][$x])))
          {
            $rowspancells[$y][$x]+=1;
            $z+=1;
          }
        }
      }

      // if the cell itself if :::, then its rowspan value is 0
      for ($y=1;$y<count($cells)+1;$y=$y+1)
        for ($x=0;$x<count($cells[$y]);$x=$x+1)
          if (preg_match("/ *::: */",$cells[$y][$x])) $rowspancells[$y][$x]=0;

      // display them
      for ($y=1;$y<count($cells)+1;$y=$y+1)
      {
        for ($x=0;$x<count($cells[$y]);$x=$x+1)
          echo $rowspancells[$y][$x];
        echo "\n";
      }

      // begin display the mediawiki table
      $tablesource="{| class=\"wikitable sortable\" border=1\n";
      if ($headers!="")
      {
        $tablesource.="!";
        for ($n=0;$n<count($headers);$n=$n+1)
        {
          $tablesource.=$headers[$n]; 
          if ($n<count($headers)-1) $tablesource.="!!";
        }
        $tablesource.="\n|-\n";
      }
      for ($y=1;$y<count($cells)+1;$y=$y+1)
      {
        $tablesource.="| ";
        for ($x=0;$x<count($cells[$y]);$x=$x+1)
        {
          if ($rowspancells[$y][$x]>=1)
	  {
            if ($rowspancells[$y][$x]>1) $tablesource.="rowspan=".$rowspancells[$y][$x]."|";
	    $tablesource.=$cells[$y][$x];
	    if ($x<count($cells[$y])-1) $tablesource.=" || ";
	  }
        }
        $tablesource.="\n|-\n";
      }
      $tablesource.="|}\n";
      echo $tablesource;
      $output.=$tablesource;
      //end display mediawiki table

      $headers="";
      $cells="";
      $row=0;
    } // endif have we left a table

    // replace tables
    if (preg_match("/^\^/",$line))
    {
      $in_table=true;
      $row=0; // the header row is row 0. It may exist or not.
      $line=preg_replace("/^\^/","",$line);
      $line=preg_replace("/\n/","",$line);
      $line=preg_replace("/\^$/","",$line);
      $headers=explode("^",$line);
    }

    if ($in_table) $line=""; // if this is in a table then the table's content will be stored in $headers and $cells
    // end care for tables

    $output.=$line;
  } //while (++$i<$linecount)
  // is the end of file also an end of table?
  $outputfile=fopen($filename.".mod","w");
  fwrite($outputfile,$output);
  fclose ($outputfile);
}
?>
