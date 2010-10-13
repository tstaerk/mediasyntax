<?php
// dokuwiki2mediawiki.php (c) 2010 by Thorsten Staerk
// This program reads files containing dokuwiki syntax and converts them into files
// containing mediawiki syntax.
// The source file is given by parameter, the target file is the source file plus a
// ".mod" suffix.

// TODO:
// filename: not only one file name!
// read: not only 4096 bytes
// test if we overwrite a file
// test if argv[1] is empty

if ($argc==1)
{
  echo "dokuwiki2mediawiki.php (c) 2010 by Thorsten Staerk";
  echo "Usage: php dokuwiki2mediawiki <file>";
  echo "Example: php dokuwiki2mediawiki start.txt";
}
$filename=$argv[1];
$inputfile=fopen($filename,"r");
$outputfile=fopen($filename.".mod","w");
if ($inputfile)
{
  if ($outputfile)
  {
    while (!feof($inputfile))
    {
      $line=fgets($inputfile,4096);

      // replace headings
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

      fwrite($outputfile,$line);
    }
  }
}
fclose ($inputfile);
fclose ($outputfile);
?>