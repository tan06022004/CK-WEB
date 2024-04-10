<?php
// Check if 'id' parameter is set and is a non-empty string
if(isset($_GET['id']) && !empty($_GET['id'])) {
    // Include database connection
    include('admin/db_connect.php');
    
    // Sanitize 'id' parameter
    $id = $conn->real_escape_string($_GET['id']);
    
    // Query database
    $qry = $conn->query("SELECT * FROM product_list WHERE id = '$id'");
    
    // Check if query was successful
    if($qry) {
        // Fetch data
        $result = $qry->fetch_array();
        // Process fetched data here
    } else {
        // Handle case where query fails
        //echo "Error executing query: " . $conn->error;
    }
    
    // Close database connection
    $conn->close();
} 
?>

<?php if(isset($result)): ?>
<div class="container-fluid">
    <div class="card">
        <img src="assets/img/<?php echo $result['img_path'] ?>" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title"><?php echo $result['name'] ?></h5>
            <p class="card-text truncate"><?php echo $result['description'] ?></p>
            <p class="card-text">Price: $<?php echo $result['price'] ?></p>
            <div class="form-group">
            </div>
            <div class="row">
                <div class="col-md-2"><label class="control-label">Qty</label></div>
                <div class="input-group col-md-7 mb-3">
                    <div class="input-group-prepend">
                        <button class="btn1 btn-outline-secondary" type="button" id="qty-minus"><span class="fa fa-minus"></button>
                    </div>
                    <input type="number" readonly value="1" min="1" class="form-control text-center" name="qty" >
                    <div class="input-group-prepend">
                        <button class="btn1 btn-outline-secondary" type="button" id="qty-plus"><span class="fa fa-plus"></span></button>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <button class="btn btn-outline-primary btn-sm btn-block" id="add_to_cart_modal"><i class="fa fa-cart-plus"></i> Add to Cart</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<style>
    #uni_modal_right .modal-footer{
        display: none;
    }
</style>

<script>
    $('#qty-minus').click(function(){
        var qty = $('input[name="qty"]').val();
        if(qty == 1){
            return false;
        } else {
            $('input[name="qty"]').val(parseInt(qty) -1);
        }
    })
    $('#qty-plus').click(function(){
        var qty = $('input[name="qty"]').val();
        $('input[name="qty"]').val(parseInt(qty) +1);
    })
    $('#add_to_cart_modal').click(function(){
        start_load()
        $.ajax({
            url:'admin/ajax.php?action=add_to_cart',
            method:'POST',
            data:{pid:'<?php echo $_GET['id'] ?>',qty:$('[name="qty"]').val()},
            success:function(resp){
                if(resp == 1)
                    alert_toast("Order successfully added to cart");
                    $('.item_count').html(parseInt($('.item_count').html()) + parseInt($('[name="qty"]').val()))
                    $('.modal').modal('hide')
                    end_load()
            }
        })
    })
</script>
