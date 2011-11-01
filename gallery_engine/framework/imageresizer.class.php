<?php
/**
 * Class to manage Image Resizing
 */
class ImageResizer
{
	public static function resizeImage($file, $img_height, $waterMark, $image_quality = 70, $filename = null )
	{
		// Getting the image temp.
		if (strtolower(substr($file, strlen($file)-3)) == 'jpg')
		{
			$img_temp = imagecreatefromjpeg($file);
		}
		else if (strtolower(substr($file, strlen($file)-3)) == 'gif')
		{
			$img_temp = imagecreatefromgif($file);
		}
		else if (strtolower(substr($file, strlen($file)-3)) == 'png')
		{
			$img_temp = imagecreatefrompng($file);
		}
		else if (strtolower(substr($file, strlen($file)-3)) == 'bmp')
		{
			$img_temp = imagecreatefrombmp($file);
		}
		
		// Get the proportions for the Thumb.
		$image_temp = array(
			'x'	=> imagesx( $img_temp ),
			'y'	=> imagesy( $img_temp )
		);
		$img_width = $image_temp['x'] / $image_temp['y'] * $img_height;
		$img_thumb = imagecreatetruecolor($img_width, intval( $img_height ) );

		// Generate the thumb.
		imagecopyresampled($img_thumb, $img_temp, 0, 0, 0, 0, $img_width, $img_height, $image_temp['x'], $image_temp['y'] );

		// Watermark related.
		if ( '' === $watermark || is_null( $watermark ) )
		{
			// Get the color for the Watermark
			$black = @imagecolorallocate ($img_temp, 0, 0, 0);
			$white = @imagecolorallocate ($img_temp, 255, 255, 255);
			// Font for the Watermark
			$font = 2;
			// Where to start drawing the Watermark
			$originx = imagesx($img_thumb) - 100;
			$originy = imagesy($img_thumb) - 15;
			// Create the Watermark.
			@imagestring ($img_thumb, $font, $originx + 10, $originy, $waterMark, $black);
			@imagestring ($img_thumb, $font, $originx + 11, $originy - 1, $waterMark, $white);
		}

		// Using headers to dump to the browser the image if there is no filename specified
		if ( is_null( $filename ) )
		{
			if (strtolower(substr($file, strlen($file)-3)) == 'jpg')
			{
				header ("Content-type: image/jpeg");
			}
			else if (strtolower(substr($file, strlen($file)-3)) == 'gif')
			{
				header ("Content-type: image/gif");
			}
			else if (strtolower(substr($file, strlen($file)-3)) == 'png')
			{
				header ("Content-type: image/png");
			}
			else if (strtolower(substr($file, strlen($file)-3)) == 'bmp')
			{
				header ("Content-type: image/bmp");
			}
		}

		// Finally write the file.
		imagejpeg($img_thumb, $filename, $image_quality);
		// Destroy the memory file.
		imagedestroy ($img_thumb); 
	}
	
	protected static function imagecreatefrombmp($file)
	{
		global  $CurrentBit, $echoMode;
		
		$f=fopen($file,"r");
		$Header=fread($f,2);
		
		if($Header=="BM")
		{
			$Size=self::freaddword($f);
			$Reserved1=self::freadword($f);
			$Reserved2=self::freadword($f);
			$FirstByteOfImage=self::freaddword($f);
			
			$SizeBITMAPINFOHEADER=self::freaddword($f);
			$Width=self::freaddword($f);
			$Height=self::freaddword($f);
			$biPlanes=self::freadword($f);
			$biBitCount=self::freadword($f);
			$RLECompression=self::freaddword($f);
			$WidthxHeight=self::freaddword($f);
			$biXPelsPerMeter=self::freaddword($f);
			$biYPelsPerMeter=self::freaddword($f);
			$NumberOfPalettesUsed=self::freaddword($f);
			$NumberOfImportantColors=self::freaddword($f);
		
			if($biBitCount<24)
			{
				$img=imagecreate($Width,$Height);
				$Colors=pow(2,$biBitCount);
				for($p=0;$p<$Colors;$p++)
				{
					$B=self::freadbyte($f);
					$G=self::freadbyte($f);
					$R=self::freadbyte($f);
					$Reserved=self::freadbyte($f);
					$Palette[]=imagecolorallocate($img,$R,$G,$B);
				}
				
				if($RLECompression==0)
				{
					$Zbytek=(4-ceil(($Width/(8/$biBitCount)))%4)%4;
					
					for($y=$Height-1;$y>=0;$y--)
					{
						$CurrentBit=0;
						for($x=0;$x<$Width;$x++)
						{
							$C=freadbits($f,$biBitCount);
							imagesetpixel($img,$x,$y,$Palette[$C]);
						}
						if($CurrentBit!=0) {self::freadbyte($f);};
						for($g=0;$g<$Zbytek;$g++)
							self::freadbyte($f);
					}
				}
			}
		
		
			if($RLECompression==1) //$BI_RLE8
			{
				$y=$Height;
				
				$pocetb=0;
				
				while(true)
				{
					$y--;
					$prefix=self::freadbyte($f);
					$suffix=self::freadbyte($f);
					$pocetb+=2;
					
					$echoit=false;
					
					if($echoit)echo "Prefix: $prefix Suffix: $suffix<BR>";
					if(($prefix==0)and($suffix==1)) break;
					if(feof($f)) break;
					
					while(!(($prefix==0)and($suffix==0)))
					{
						if($prefix==0)
						{
							$pocet=$suffix;
							$Data.=fread($f,$pocet);
							$pocetb+=$pocet;
							if($pocetb%2==1) {self::freadbyte($f); $pocetb++;};
						}
						if($prefix>0)
						{
							$pocet=$prefix;
							for($r=0;$r<$pocet;$r++)
							$Data.=chr($suffix);
						}
						$prefix=self::freadbyte($f);
						$suffix=self::freadbyte($f);
						$pocetb+=2;
						if($echoit) echo "Prefix: $prefix Suffix: $suffix<BR>";
					}
					
					for($x=0;$x<strlen($Data);$x++)
					{
						imagesetpixel($img,$x,$y,$Palette[ord($Data[$x])]);
					}
					$Data="";
				}
			}
		
			if($RLECompression==2) //$BI_RLE4
			{
				$y=$Height;
				$pocetb=0;
				
				/*while(!feof($f))
				echo self::freadbyte($f)."_".self::freadbyte($f)."<BR>";*/
				while(true)
				{
					//break;
					$y--;
					$prefix=self::freadbyte($f);
					$suffix=self::freadbyte($f);
					$pocetb+=2;
					
					$echoit=false;
					
					if($echoit)echo "Prefix: $prefix Suffix: $suffix<BR>";
					if(($prefix==0)and($suffix==1)) break;
					if(feof($f)) break;
					
					while(!(($prefix==0)and($suffix==0)))
					{
						if($prefix==0)
						{
							$pocet=$suffix;
							
							$CurrentBit=0;
							for($h=0;$h<$pocet;$h++)
							$Data.=chr(freadbits($f,4));
							if($CurrentBit!=0) freadbits($f,4);
							$pocetb+=ceil(($pocet/2));
							if($pocetb%2==1) {self::freadbyte($f); $pocetb++;};
						}
						if($prefix>0)
						{
							$pocet=$prefix;
							$i=0;
							for($r=0;$r<$pocet;$r++)
							{
								if($i%2==0)
								{
									$Data.=chr($suffix%16);
								}
								else
								{
									$Data.=chr(floor($suffix/16));
								}
								$i++;
							}
						}
						$prefix=self::freadbyte($f);
						$suffix=self::freadbyte($f);
						$pocetb+=2;
						if($echoit) echo "Prefix: $prefix Suffix: $suffix<BR>";
					}
					
					for($x=0;$x<strlen($Data);$x++)
					{
						imagesetpixel($img,$x,$y,$Palette[ord($Data[$x])]);
					}
					$Data="";
				}
			}
		
		
			if($biBitCount==24)
			{
				$img=imagecreatetruecolor($Width,$Height);
				$Zbytek=$Width%4;
				
				for($y=$Height-1;$y>=0;$y--)
				{
					for($x=0;$x<$Width;$x++)
					{
						$B=self::freadbyte($f);
						$G=self::freadbyte($f);
						$R=self::freadbyte($f);
						$color=imagecolorexact($img,$R,$G,$B);
						if($color==-1) $color=imagecolorallocate($img,$R,$G,$B);
						imagesetpixel($img,$x,$y,$color);
					}
					for($z=0;$z<$Zbytek;$z++)
						self::freadbyte($f);
				}
			}
			return $img;
		
		}
		
		fclose($f);
	}
	
	private static function freaddword($f)
	{
		$b1=$this->freadword($f);
		$b2=$this->freadword($f);
		return $b2*65536+$b1;
	}
	
	private static function freadword($f)
	{
		$b1=self::freadbyte($f);
		$b2=self::freadbyte($f);
		return $b2*256+$b1;
	}
	
	private static function freadbyte($f)
	{
		return ord(fread($f,1));
	}
}
?>