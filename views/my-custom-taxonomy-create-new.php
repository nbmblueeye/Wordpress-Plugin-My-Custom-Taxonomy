<div class="my_custom_taxonomy_container">
    <div class="my_custom_taxonomy_header"><h5 class="text-success">Add a New Custom Taxonomy to a Existing Post Type</h5></div>

    <form id="my_custom_taxonomy_create_new">
        <div class="my_custom_taxonomy_message" style="display: none;"></div>
        <div class="my_custom_taxonomy_error" style="display: none; color: red"></div>
        <div class="my_custom_taxonomy_content">

            <div class="form_group mct_info">
                <Label>Existing Post Type</Label>
                <input type="text" class="mct_post_type_slug" name="mct_post_type_slug" placeholder="Add Existing Custom Post Type">
            </div>

            <div class="form_group mct_info">
                <Label>New Taxonomy Name</Label>
                <input type="text" class="mct_taxonomy_name" name="mct_taxonomy_name" placeholder="Add a New Taxonomy Name">
            </div>

            <div class="form_group mct_info">
                <Label>New Taxonomy Slug</Label>
                <input type="text" class="mct_taxonomy_slug" name="mct_taxonomy_slug" placeholder="Add a Slug for New Taxonomy">
            </div>

            <div class="form_group_radio mct_info">
                <Label>Select Hierarchical (Category | Tag)</Label>
                <div class="radio_box">
                    <div></div>
                    <div>
                        <input type="radio" class="mct_hierarchical" name="mct_hierarchical" value="true"><span>Category</span>
                    </div>
                </div>
                <div class="radio_box">
                    <div></div>
                    <div>
                        <input type="radio" class="mct_hierarchical" name="mct_hierarchical" value="false"><span>Tag</span>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="my_custom_taxonomy_action_btn">
            <button type="submit" class="my_custom_taxonomy_save_btn">Save Taxonomy</button>
        </div>
    </form>
</div>