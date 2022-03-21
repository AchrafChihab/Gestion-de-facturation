$(document).ready(function() {
  $('#table2').DataTable( {
              "order": [[ 3, "desc" ]]
          } );
  } );


// ajoute plusieur service 



const addFormToCollection = (e) => {
 
  const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);
   
  const item = document.createElement('div');

  item.innerHTML = collectionHolder
    .dataset
    .prototype
    .replace(
      /__name__/g,
      collectionHolder.dataset.index
    );

  item.querySelector(".btn-remove").addEventListener("click", () => item.remove());
  collectionHolder.appendChild(item);

  collectionHolder.dataset.index++;
  
};


document
.querySelectorAll('.add_item_link')
.forEach(btn => {
    btn.addEventListener("click", addFormToCollection)
});

 
  
 
  jQuery('.lignesfacture_contenu  > .row').each(function(index) {

    var element = $(this);
    var n = jQuery(this).find(".btn-remove"); // <- This works
    
    $(n).on("click", function(){
      console.log(this);
      console.log(element);
      $(element).remove();
    });

  }); 


   // add date time  


    function getComboA(serviceelement) {
      var valueee = serviceelement.value;  
      // console.log( Boolean(valueee));
      console.log(valueee);
      $.ajax({
        url:$('#routeservicetype').data('route'),
        type: "POST",
        data:{'service': valueee},
        async: true,
        encode: true,
        success: function (data)
        {
          if( data == true){
            $(serviceelement).parent().parent().parent().parent().addClass('showinputdate');
          }else{
            $(serviceelement).parent().parent().parent().parent().removeClass('showinputdate');
          }
        }
    });
    }




