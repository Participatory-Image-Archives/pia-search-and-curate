import AppForm from '../app-components/Form/AppForm';

Vue.component('image-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                salsah_id:  '' ,
                oldnr:  '' ,
                signature:  '' ,
                title:  '' ,
                original_title:  '' ,
                file_name:  '' ,
                original_file_name:  '' ,
                salsah_date:  '' ,
                sequence_number:  '' ,
                location:  '' ,
                collection:  '' ,
                verso:  '' ,
                objecttype:  '' ,
                model:  '' ,
                format:  '' ,
                
            }
        }
    }

});