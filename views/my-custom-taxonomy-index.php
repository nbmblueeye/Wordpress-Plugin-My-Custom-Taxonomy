<?php
    global $wpdb;

    $taxonomy = new My_Custom_Taxonomy();
    $table_name = $taxonomy->reset_my_custom_taxonomy_table_name();

    $allTaxonomy = $wpdb->get_results( 
            "SELECT * FROM {$table_name} ORDER BY `id` ASC"
    );

?>
    <div class="container" style="margin-top: 6rem;">  
        <div class="row d-flex justify-content-center"> 
            <div class="col-md-8 border rounded">    
                <div class="header mb-3 p-3">
                    <h3 class="text-success">Added Taxonomy List</h3>
                    <div></div>
                </div>
                <div class="my_custom_taxonomy_index_message alert alert-success" style="display: none"></div>
                <div class="body mt-3">
                    <div class="table-responsive-md">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th  class="align-middle" scope="col">#</th>
                                    <th  class="align-middle" scope="col">Post Type</th>
                                    <th  class="align-middle" scope="col">Taxonomy Name</th>
                                    <th  class="align-middle" scope="col">Taxonomy Slug</th>
                                    <th  class="align-middle" scope="col">Category Or Tag</th>
                                    <th  class="align-middle text-center" scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($allTaxonomy) && is_array($allTaxonomy)): ?>
                                <?php foreach($allTaxonomy as $taxonomy): ?>

                                    <tr>
                                        <th  class="align-middle" scope="row"><?php echo esc_html($taxonomy->id) ?></th>
                                        <td class="align-middle" ><?php echo esc_html($taxonomy->post_type_slug) ?></td>
                                        <td class="align-middle" ><?php echo esc_html($taxonomy->taxonomy_name) ?></td>
                                        <td class="align-middle" ><?php echo esc_html($taxonomy->taxonomy_slug) ?></td>
                                        <?php
                                            $type_mct = $taxonomy->hierarchical == 'true'? true:false
                                        ?>
                                        <td class="align-middle" ><?php echo esc_html($type_mct ? "Category":"Tag") ?></td>
                                        <td class="align-middle text-center" >
                                            <div class="my_custom_taxonomy_action_box">
                                                <div class="action_btn edit_btn edit_my_custom_taxonomy_btn" data-bs-toggle="modal" data-bs-target="#modal_<?php echo esc_attr($taxonomy->id) ?>"><span><i class="fa-solid fa-pen-to-square"></i></span></div>
                                                &nbsp;
                                                <div class="action_btn delete_btn delete_my_custom_taxonomy_btn" data-taxomomy-id="<?php echo esc_attr($taxonomy->id) ?>"><span><i class="fa-solid fa-trash"></i></span></div>
                                            </div>
                                        </td>
                                    </tr>
                                        <!-- Modal -->
                                        <div class="modal fade mt-5" id="modal_<?php echo esc_attr($taxonomy->id) ?>" tabindex="-1" aria-labelledby="modal_<?php echo esc_attr($taxonomy->id) ?>Label" aria-hidden="true">
                                            <div class="modal-dialog">

                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modal_<?php echo esc_attr($taxonomy->id) ?>Label">Edit Taxonomy</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <div class="my_custom_taxonomy_update_message" style="display: none;"></div>
                                                    <div class="my_custom_taxonomy_update_error" style="display: none; color: red"></div>

                                                    <form id="update_my_custom_taxonomy">           
                                                        <div class="modal-body update_my_custom_taxonomy_box">
                                                                <input type="hidden" class="mct_update_taxonomy_id" value = <?php echo esc_attr(!empty($taxonomy->id) ? $taxonomy->id:"") ?>>
                                                                <div class="mb-3 update_my_custom_taxonomy_item">
                                                                    <Label class="col-form-label">Existing Post Type Slug</Label>
                                                                    <input type="text" class="mct_update_post_type_slug form-control" name="mct_update_post_type_slug" value = '<?php echo esc_attr(!empty($taxonomy->post_type_slug) ? $taxonomy->post_type_slug:"") ?>'>
                                                                </div>

                                                                <div class="mb-3 update_my_custom_taxonomy_item">
                                                                    <Label class="col-form-label">New Taxonomy Name</Label>
                                                                    <input type="text" class="mct_update_taxonomy_name form-control" name="mct_update_taxonomy_name" value = '<?php echo esc_attr($taxonomy->taxonomy_name ? $taxonomy->taxonomy_name : "") ?>'>
                                                                </div>

                                                                <div class="mb-3 update_my_custom_taxonomy_item">
                                                                    <Label class="col-form-label">New Taxonomy Slug</Label>
                                                                    <input type="text" class="mct_update_taxonomy_slug form-control" name="mct_update_taxonomy_slug" value = '<?php echo esc_attr(!empty($taxonomy->taxonomy_slug) ? $taxonomy->taxonomy_slug:"") ?>'>
                                                                </div>

                                                                <div class="form_group_radio update_my_custom_taxonomy_item">
                                                                    <Label>Select Hierarchical ("Category" is category | "Tag" is tag)</Label>
                                                                    <div class="radio_box"><div></div><div><input type="radio" class="mct_update_hierarchical" name="mct_update_hierarchical" value="true" <?php echo esc_attr(($taxonomy->hierarchical == "true") ? "checked":"") ?>><span>Category</span></div></div>
                                                                    <div class="radio_box"><div></div><div><input type="radio" class="mct_update_hierarchical" name="mct_update_hierarchical" value="false" <?php echo esc_attr(($taxonomy->hierarchical == "false") ? "checked":"") ?>><span>Tag</span></div></div>
                                                                </div>     
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary update_my_custom_taxonomy">Update Tax</button>
                                                        </div>
                                                    </form>  
                                                </div>
                                            </div>
                                        </div>
                                    
                                <?php endforeach; ?>   
                            <?php else: ?>
                                <tr>
                                    <td colspan ="6">
                                        <div class="text-center my-5"><h5>No Data Available!!!</h5></div>
                                    </td>
                                </tr>
                            <?php endif; ?> 
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>   
        </div>
    </div>

