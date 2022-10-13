



$(function() {
	
	$(document).keydown(function(e){
		
		//$("button").click(function(){
		// alert(" position packman: " +  posXpacman.top + " Left position: " +  posXpacman.left);
		//alert("Top position bal: " + posBal.top + " Left position: " +  posBal.left);
		// });

		var dot  = document.getElementById('dot') ;
		posX = parseInt($('.pacman').css('left'));
		var pospacman = $("#pac").offset();
		var posBal = $("#dot").offset();

		var pospacman_1 = $("#pac").offset();
		

	//	console.log(pospacman);
		console.log(pospacman_1);
		
		if (e.which == 39){ // Vers la droite
			if (posX < 1565){	
				$('.pacman').css('left', posX+20).removeClass("img_left img_up img_down " ).addClass("img_right"); 

			}
			
			//if(((pospacman.left === posBal.left) && (pospacman_1.left === posBal.left))&&((pospacman.top === posBal.left) && (pospacman_1.top === posBal.top)))
			if(((pospacman.left == posBal.left) && (pospacman.top == posBal.top))||((pospacman_1.left == posBal.left) && (pospacman_1.top == posBal.top)))
			{
				var score = $('.score span').html();
				score = parseInt(score);
				score = score + 1;
				
				$('.score span').html(score);

				do{
					dot.style.transform = "translateY(-150px)";		
			    }while(score==5)
				
			}
			
		}
		
	
				
		if (e.which == 37){// Vers la gauche
			posX = parseInt($('.pacman').css('left'));
			if (posX >20){
				$('.pacman').css('left', posX-10).removeClass("img_right img_up img_down " ).addClass("img_left");
			}
			if(((pospacman.left == posBal.left) && (pospacman.top == posBal.top))||((pospacman_1.left == posBal.left) && (pospacman_1.top == posBal.top)))
				
			{
				var score = $('.score span').html();
				score = parseInt(score);
				score = score + 1;
				
				$('.score span').html(score);

				do{
					dot.style.transform = "translateX(150px)";		
			    }while(score==5)
				
			}

		} 
		     
		if (e.which == 40) // Vers le bas
		{
			posY = parseInt($('.pacman').css('top'));
			if (posY < 710){
				$('.pacman').css('top', posY+30).removeClass("img_right img_left img_up " ).addClass("img_down");
		
			}
			
			if(((pospacman.left == posBal.left) && (pospacman.top == posBal.top))||((pospacman_1.left == posBal.left) && (pospacman_1.top == posBal.top)))
			{
				
		
				var score = $('.score span').html();
				score = parseInt(score);
				score = score + 1;
				
				$('.score span').html(score);

				do{
					dot.style.transform = "translateY(100px)";		
			    }while(score==5)
				
			}
		


		}     
		if (e.which == 38) // Vers le haut
		{
			posY = parseInt($('.pacman').css('top'));
			if (posY > 25){
				$('.pacman').css('top', posY-30).removeClass("img_right img_left img_down " ).addClass("img_up");
			}
			
			if(((pospacman.left == posBal.left) && (pospacman.top == posBal.top))||((pospacman_1.left == posBal.left) && (pospacman_1.top === posBal.top)))
				
			{
				var score = $('.score span').html();
				score = parseInt(score);
				score = score + 1;
				
				$('.score span').html(score);

				do{
					dot.style.transform = "translateY(-150px)";		
			    }while(score==5)
				
			}
		
				
			
		} 
		
		
		
	});
});

/*Ball Styling*/












//key 
/*
$(document).keydown(function(e){
	switch (e.which){
	case 37:    // flèche gauche
		$(".pacman").finish().animate({
			left: "-=10"
		});
		break;
	case 38:    // flèche haut
		$(".pacman").finish().animate({
			top: "-=10"
		});
		break;
	case 39:    // flèche droite
		$(".pacman").finish().animate({
			left: "+=10"
		});
		break;
	 case 40:    // flèche du bas
		$(".pacman").finish().animate({
			top: "+=10"
		});
		break;
	}
});*/