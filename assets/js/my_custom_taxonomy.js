jQuery(document).ready(function($){

       
        $("#my_custom_taxonomy_create_new").on("submit", function(e){

            e.preventDefault();

            let data = my_custom_taxonomy_getData();
           
            if(data){

                $.ajax({
                    method: 'POST',
                    url: mct_rest_object.rest_url + "add_my_custom_taxonomy/v1/add",
                    headers:{
                        'X-WP-Nonce':mct_rest_object.rest_nonce
                    },
                    data: data
                })
                .done(res=>{

                    $(".my_custom_taxonomy_message").html(res);
                    $(".my_custom_taxonomy_message").show();
                    $(".my_custom_taxonomy_error").html("");
                    $(".my_custom_taxonomy_error").hide();
                    
                    setTimeout(function(){
                        $(".my_custom_taxonomy_message").html("");
                        $(".my_custom_taxonomy_message").hide();

                        $(".my_custom_taxonomy_content .mct_info").find(".mct_post_type_slug").val("");
                        $(".my_custom_taxonomy_content .mct_info").find(".mct_taxonomy_name").val("");
                        $(".my_custom_taxonomy_content .mct_info").find(".mct_taxonomy_slug").val("");

                        $(".my_custom_taxonomy_content .mct_info").find(".radio_box").each(function(){
                            $(this).find(".mct_hierarchical").prop('checked', false);  
                        });
                
                        window.location.reload();

                    },3000);
                    
                })

            }else{
                return false;
            }

        });


       function my_custom_taxonomy_getData(){

            let items = {
                mct_post_type_slug: "",
                mct_taxonomy_name: "",
                mct_taxonomy_slug: "",
                mct_hierarchical: true
            };
        
            let post_type_slug = $(".my_custom_taxonomy_content .mct_info").find(".mct_post_type_slug").val();
            let taxonomy_name = $(".my_custom_taxonomy_content .mct_info").find(".mct_taxonomy_name").val();
            let taxonomy_slug = $(".my_custom_taxonomy_content .mct_info").find(".mct_taxonomy_slug").val();

            let post_type_name = post_type_slug.charAt(0).toUpperCase() + post_type_slug.slice(1);

            if(!post_type_slug || !taxonomy_name || !taxonomy_slug){
                $(".my_custom_taxonomy_error").html("Please Fill up Input Field!!!");
                $(".my_custom_taxonomy_error").show();
                return false;
            }

            if(!window.confirm(`Do you want to add new Custom Taxonomy ${taxonomy_name} to Post Type ${post_type_name} ?`)){
                return false;
            }
               

            $(".my_custom_taxonomy_content .mct_info").find(".radio_box").each(function(){
                if($(this).find(".mct_hierarchical").prop('checked')){
                    items.mct_hierarchical = $(this).find(".mct_hierarchical").val();
                }
            });

            items.mct_post_type_slug = post_type_slug;
            items.mct_taxonomy_name = taxonomy_name;
            items.mct_taxonomy_slug = taxonomy_slug; 
                
            return items;

       }    
});


jQuery(document).ready(function($){

  
    $(".update_my_custom_taxonomy").on("click", function(e){

        e.preventDefault();

        let data = my_custom_taxonomy_UpdateInfo($(this));

        if(data){

            $.ajax({
                method: 'POST',
                url: mct_rest_object.rest_url + "add_my_custom_taxonomy/v1/update",
                headers:{
                    'X-WP-Nonce':mct_rest_object.rest_nonce
                },
                data: data
            })
            .done(res=>{
                $(".my_custom_taxonomy_update_message").html(res);
                $(".my_custom_taxonomy_update_message").show();
                $(".my_custom_taxonomy_update_error").html("");
                $(".my_custom_taxonomy_update_error").hide();
                
                setTimeout(function(){
                    $(".my_custom_taxonomy_update_message").html("");
                    $(".my_custom_taxonomy_update_message").hide();
                    window.location.reload();
                },2000);

            })

        }else{
            return false;
        }

    });

    function my_custom_taxonomy_UpdateInfo(object){

        let items = {
            mct_post_type_slug: "",
            mct_taxonomy_name: "",
            mct_taxonomy_slug: "",
            mct_taxonomy_id: "",
            mct_hierarchical: true
        };
        
        
        let post_type_slug = $(object).parent().prev().find(".mct_update_post_type_slug").val();
        let taxonomy_name = $(object).parent().prev().find(".mct_update_taxonomy_name").val();
        let taxonomy_slug = $(object).parent().prev().find(".mct_update_taxonomy_slug").val();
        let taxonomy_id = $(object).parent().prev().find(".mct_update_taxonomy_id").val();

        if(!post_type_slug || !taxonomy_name || !taxonomy_slug){
            $(".my_custom_taxonomy_update_error").html("Please correct input field");
            $(".my_custom_taxonomy_update_error").show();
            return false;
        }

        if(!window.confirm(`Are You sure, You want to change this Custom Taxonomy ?`)){
            return false;
        }

        items.mct_post_type_slug    = post_type_slug;
        items.mct_taxonomy_name     = taxonomy_name;
        items.mct_taxonomy_slug     = taxonomy_slug;
        items.mct_taxonomy_id       = taxonomy_id;

        
        $(object).parent().prev().find(".radio_box").each(function(){
            if($(this).find(".mct_update_hierarchical").prop('checked')){
                items.mct_hierarchical = $(this).find(".mct_update_hierarchical").val();
            }
        });

        return items;

    }


    $(".delete_my_custom_taxonomy_btn").on("click", function(e){

            e.preventDefault();

            let taxonomy_id = $(this).data("taxomomyId");
            
            if(!confirm("Are you sure, you want to detele this Taxonomy ?")){
                return false;
            }else{
                
                $.ajax({
                    method: 'DELETE',
                    url: mct_rest_object.rest_url + "add_my_custom_taxonomy/v1/delete",
                    headers:{
                        'X-WP-Nonce':mct_rest_object.rest_nonce
                    },
                    data: {'id': taxonomy_id},
                   
                })
                    .done(res=>{

                        setTimeout(function(){
                            $(".my_custom_taxonomy_index_message").html(res);
                            $(".my_custom_taxonomy_index_message").fadeIn(1000);
                          
                        },1500);

                        setTimeout(function(){
                            $(".my_custom_taxonomy_index_message").html("");
                            $(".my_custom_taxonomy_index_message").hide();
                            window.location.reload();
                        },2000);
                })

            }
    });

   

});