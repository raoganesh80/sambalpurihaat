$().ready(function() {
    $('#add').click(function() {
      return !$('#select1 option:selected').remove().appendTo('#select2');
    });
    
    $('#remove').click(function() {
      return !$('#select2 option:selected').remove().appendTo('#select1');
    });

    $('#btnSaveChanges').click(function(){
        var names=[];
        $("#select2 option").each(function(){
            // Add $(this).val() to your list
            names.push(new Array($(this).val(), $(this).text()));
        });
        $.ajax({
            url: "modals/actionPromotionSuppliers.php",
            method: "GET",
            data: { "data":names},
            success: function (response) {
                alert(response);
                //location.reload();
            }
        });
    });

    
    
    //let $select1 = $('#select1');
    let $select2 = $('#select2');
    
    // $('#up1').click(function () {
    //   let $selected = $select1.find('option:selected');
    //   $selected.insertBefore($selected.prev());
    // });
    
    // $('#down1').click(function () {
    //   let $selected = $select1.find('option:selected');
    //   $selected.insertAfter($selected.next());
    // });
    
    $('#up2').click(function () {
      let $selected = $select2.find('option:selected');
      $selected.insertBefore($selected.prev());
    });
    
    $('#down2').click(function () {
      let $selected = $select2.find('option:selected');
      $selected.insertAfter($selected.next());
    });
  });