$(document).ready(function () {
    var assetBaseUrl ='http://3.13.59.159/schoolsharks/';					
	//var assetBaseUrl ='http://localhost/schoolshark/';

    $('.rating-star-1').hillRate();
    
    $('.rating-star-2').hillRate({
        stars : 3,
        valuesStar : [1,[2,3],[4,5]]
    });
    
    $('.rating-star-3').hillRate({
        stars : 5,
        valuesStar : [[.5,1],[1.5,2.0],[2.5,3.0],[3.5,4.0],[4.5,5.0]],
        imageStar: {"default":assetBaseUrl + 'public/sites/images/star-empty-gold.png',"full":assetBaseUrl + 'public/sites/images/star-full-gold.png',"half":assetBaseUrl + 'public/sites/images/star-half-gold.png'} ,
        /*imageStarOnIndex: [{"index":3,"default":assetBaseUrl + 'public/sites/images/star-empty-gold.png',
                "full":assetBaseUrl + 'public/sites/images/star-full-gold.png', 
                "half":assetBaseUrl + 'public/sites/images/star-half-gold.png'}],
		 nameInput: "rating",*/
        responsive: true,
        showSelectedValue:true
    });
    
    $('.rating-star-4').hillRate({
        stars : 3,
        valuesStar : [0,50,100],
        titleStar: [[":("],[':)'],[':D']] 
    });
    
    // $('.rating-star-5').hillRate({
    //     valuesStar : [0,20,40],
    //     edit:false
    // });
    $('.rating-star-5').hillRate({
        stars : 5,
        valuesStar : [[5,10],[15,20],[25,30],[35,40],[45,50]],
        imageStar: {"default":assetBaseUrl + 'public/sites/images/star-empty-gold.png',
            "full":assetBaseUrl + 'public/sites/images/star-full-gold.png',
            "half":assetBaseUrl + 'public/sites/images/star-half-gold.png'} ,
        edit:false
    });
    $('.rating-star-6').hillRate({
        imageStar: {"default":'images/star-empty-gold.png',
            "full":"images/star-full-gold.png",
            "half":"images/star-half-gold.png"} ,
        showSelectedValue:true,
        responsive: true
    });
    
    
    $('.rating-star-7').hillRate({  
        stars : 6, 
        imageStar: {"default":'images/star-empty-gold.png',"full":"images/star-full-gold.png","half":"images/star-half-gold.png"} ,
        imageStarOnIndex: [{"index":0,"default":'./images/star-empty.png',"full":"images/star-full.png","half":"images/star-half.png","state_unselected":"default"}], 
        valuesStar : [0,[1,2],[3,4],[5,6],[7,8],[9,10]],  
        titleStar: [["Insufficient"],["almost enough","Enough"],["More than enough","Good"],["More than good","Exceptional"],["Extraordinary","excellent"],["Incredible","Wow!"]], 
        nameInput: "rating",
        responsive: true,
        showSelectedValue:false
    });
  
});