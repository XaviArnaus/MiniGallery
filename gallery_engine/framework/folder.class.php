<?php
/**
 * Class to control the each Folder
 */
class Folder extends Item
{
	protected $dirname;

	public function printRawIcon()
	{
			$icon_folder  = 'R0lGODlhLwAvAPcAAHRwZnRwcHlwRnl2e3l8e39wMX98e3+ChoVwMYVwO4WChoWIhotwMYt2Jot8RouI';
			$icon_folder .= 'houOkJF2JpGOkJGUkJd2Jpd8JpeUkJeUm518G52IO52bm52hm6OCG6OUZqOhpqmCEamIG6mnpqmtsK+I';
			$icon_folder .= 'Ea+tsLWIEbWhW7Wtm7WzsLWzu7uOBruUJru5u7u/u8GUBsG/u8G/xcebEcfFxcfMxc2bBs2hEc2hG82n';
			$icon_folder .= 'Mc2nO83M0NOhEdOnJtOtO9OzRtOzUdOzW9PS0NPY29mnG9mnJtmtG9mtJtmtMdmzJtmzO9m5W9m5Ztm/';
			$icon_folder .= 'cNnFe9nY29+tJt+zMd+5Ud+/O9+/Ud+/W9+/Zt/Fe9/Yxd/e29/e5d/k5eWzMeW5MeW5O+W/W+XFRuXF';
			$icon_folder .= 'ZuXFcOXMUeXMW+XMe+XMhuXSm+Xk5eu/RuvFRuvFZuvMZuvSUevSZuvShuvSkOvYZuvYkOveu+vk2+vq';
			$icon_folder .= '5evq8Ovw8PG/UfHSe/HYm/HecPHem/HesPHkcPHkkPHku/Hw8PfMW/fepvfqe/fqm/fqxffq0Pfwhvfw';
			$icon_folder .= '0Pfw2/f28Pf2+/3MZv3ScP3Ye/3ee/3ke/3kkP3qhv3qkP3qpv3wkP32kP39m/39u/390P39+wEBAQEB';
			$icon_folder .= 'AQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEB';
			$icon_folder .= 'AQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEB';
			$icon_folder .= 'AQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEB';
			$icon_folder .= 'AQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEB';
			$icon_folder .= 'AQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQAA';
			$icon_folder .= 'ACH5BAEAAP8ALAAAAAAvAC8AQAj/AP8JHEiwoMGDCBMinDImk6WHEB9iSmTjRJw4ZKrwuLEDCZgxgfxk';
			$icon_folder .= 'yoSphoc5CgfyCDQpzxMaNZB8abMn0UhMl3Lq3Mnz0iJMcDgouKIwTpcuYC5VWsq0qdOnThexwbAgS8p/';
			$icon_folder .= 'MWgQcsS1q9evYLsCQnOERoUHZq7++8PiAQQPLYBgmWNTrd27ePMepJHoUsS/EKPU6DHGjR9EIxMrXjSy';
			$icon_folder .= 'hgAUfxIyadMGCSbAmDP//emYRSKFiooU0UMpkunTqFOrTr2IBoAXiq7yMDKJke3bjOxwEULDBQcEAgAE';
			$icon_folder .= 'WOCBBZArc/5k0su8ufPndqfs6EGFjOFCAt2smBEb+sEpcC5r/7aEiSTOnJYq8XkTZktZGj2gkNljxEST';
			$icon_folder .= '5QhxIJoEdamgNTUQYh4mBBZoIIGMHeJCAEDgd1APeLRRA4E9VWjhTj9JwcAGdChUxVFQiDfeiBAtMogK';
			$icon_folder .= 'AQTh4EFxiDbGJZTEKOOMNNZI4yJPJOBBhynRQEMgkqwm5JCm2VFCAE2oxUMNlzTi5JNQRrkIGlvwVgIF';
			$icon_folder .= 'AgRnwAQhyGDGigktMYQaTuhAwwdYCnfABSTI0IQZdSgCpnd01mnnnXjm6Z0fGFWRxEY02GBFd3oiUoNN';
			$icon_folder .= 'A+qUCRQdXDEnIn6UUUUPPPjowxxzHoSIEH1phgkhSEAxRht+HHLTeQ8ZwgcNGdzwgxyZDv/El18kCsKe';
			$icon_folder .= 'ez4aQRgefiCKWCY9OMBCZAjRgAiJXhyq2IEHMpaJazAQahAPh1DClCB5ePEEETBBISCz4CKIyR4fDJBk';
			$icon_folder .= 'QjzgMQkNU5g6IIUXWvgTGBFMkFaYcNyBRF/x9oshJkgIIAKxCH2YhmUkJmzJIpe4NkOsApVxFBgiKqzZ';
			$icon_folder .= 'In2MQABRRT3xRFL9hRzyImJEYMG9CbU42iSGtOzyyzDHDDOOApBAMEKZDEGDHoMQ6TNrruUAsaxaQfLI';
			$icon_folder .= '0UgnrfTSStsxggEcp4SDVmFV7RUja0TBrQoYMCBAACLwmNJshOBmtm2AnFHmmQ0EF4ACGqQww5t/yGmX';
			$icon_folder .= 'ErTZtogdWlgtiUEBwQ0gQQgwAJEFHXU9x0QMIETg9gJx53CFGcrp+U8mdExOh92Wd+75nQEBADs=';
			header('Content-type: image/gif');
			echo base64_decode($icon_folder);
	}
}
?>