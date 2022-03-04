$(document).ready(function() {
  $('#table2').DataTable();
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




