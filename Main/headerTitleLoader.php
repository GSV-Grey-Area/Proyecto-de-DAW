<?php
	echo '#cf img
		{
			-webkit-transition: opacity 1s ease-in-out;
			-moz-transition: opacity 1s ease-in-out;
			-o-transition: opacity 1s ease-in-out;
			transition: opacity 1s ease-in-out;
			
			animation-name: example2;
			animation-duration: 10s;
			animation-iteration-count: infinite;
						
			height: 125px;
			width: 100%;
			position: absolute;
			left: 0;
			object-fit: cover;
		}
		
		@keyframes example2
		{
			0% {opacity: 1}
			50% {opacity: 0}
			100% {opacity: 1}
		}
			
		.testDiv
		{
			height: 125px;
			
			/*-webkit-mask:url(\'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" ><text id="mask" x="0" y="180" font-family="Cambria, sans-serif" font-size="250">' . $title . '</text></svg>\') center center / cover;*/
			
			/*-webkit-mask-image:url("../Other/Concepts/[2024-04-13] SVG text mask test 00/1.svg");*/
			-webkit-mask-image:url("X.svg");
			-webkit-mask-size: 100% 100%;
			-webkit-mask-repeat: no-repeat;
			
		}';
?>