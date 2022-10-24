<?php echo $this->form('search-form', $options['form_attributes']); ?>
    <?php echo $this->formText('query', $filters['query'], array('title' => __('Search'))); ?>
    <?php echo $this->formButton('submit_search', $options['submit_value'], array('type' => 'submit')); ?>
</form>
