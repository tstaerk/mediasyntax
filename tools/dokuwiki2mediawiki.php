<?php
// dokuwiki2mediawiki.php (c) 2010-2012 by Thorsten Staerk
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
      $lines[$i]=preg_replace("/^  /","",$line);
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
    }

    $line=preg_replace("/^-/","#",$line);
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

    if (preg_match("/^\|/",$line))
    {
      if (!$in_table) {$output.="{| class=\"wikitable sortable\" border=1\n";}
      $line=preg_replace("/\|$/","",$line);
      $line=preg_replace("/\|/","||",$line);
      $line=preg_replace("/^\|\|/","|-\n| ",$line);
      $in_table=true;
    }
    // have we left a table?
    if ((!preg_match("/^\|/",$line)) && $in_table)
    {
      $line="|-\n|}\n".$line;
      $in_table=false;
    }
    // replace tables
    if (preg_match("/^\^/",$line))
    {
      $output.="{| class=\"wikitable sortable\" border=1\n";
      $line=preg_replace("/^\^/","!",$line);
      $line=preg_replace("/\^/","!!",$line);
      $in_table=true;
    }

    $output.=$line;
  } //while (++$i<$linecount)
  // is the end of file also an end of table?
  if ($in_table) {$output.="\n|-\n|}\n";}
  $outputfile=fopen($filename.".mod","w");
  fwrite($outputfile,$output);
  fclose ($outputfile);
  echo $output;
}
?>
