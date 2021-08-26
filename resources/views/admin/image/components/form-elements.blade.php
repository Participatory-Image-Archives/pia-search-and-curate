<div class="form-group row align-items-center" :class="{'has-danger': errors.has('salsah_id'), 'has-success': fields.salsah_id && fields.salsah_id.valid }">
    <label for="salsah_id" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.image.columns.salsah_id') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.salsah_id" v-validate="'integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('salsah_id'), 'form-control-success': fields.salsah_id && fields.salsah_id.valid}" id="salsah_id" name="salsah_id" placeholder="{{ trans('admin.image.columns.salsah_id') }}">
        <div v-if="errors.has('salsah_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('salsah_id') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('oldnr'), 'has-success': fields.oldnr && fields.oldnr.valid }">
    <label for="oldnr" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.image.columns.oldnr') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.oldnr" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('oldnr'), 'form-control-success': fields.oldnr && fields.oldnr.valid}" id="oldnr" name="oldnr" placeholder="{{ trans('admin.image.columns.oldnr') }}">
        <div v-if="errors.has('oldnr')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('oldnr') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('signature'), 'has-success': fields.signature && fields.signature.valid }">
    <label for="signature" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.image.columns.signature') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.signature" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('signature'), 'form-control-success': fields.signature && fields.signature.valid}" id="signature" name="signature" placeholder="{{ trans('admin.image.columns.signature') }}">
        <div v-if="errors.has('signature')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('signature') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('title'), 'has-success': fields.title && fields.title.valid }">
    <label for="title" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.image.columns.title') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.title" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('title'), 'form-control-success': fields.title && fields.title.valid}" id="title" name="title" placeholder="{{ trans('admin.image.columns.title') }}">
        <div v-if="errors.has('title')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('title') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('original_title'), 'has-success': fields.original_title && fields.original_title.valid }">
    <label for="original_title" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.image.columns.original_title') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.original_title" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('original_title'), 'form-control-success': fields.original_title && fields.original_title.valid}" id="original_title" name="original_title" placeholder="{{ trans('admin.image.columns.original_title') }}">
        <div v-if="errors.has('original_title')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('original_title') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('file_name'), 'has-success': fields.file_name && fields.file_name.valid }">
    <label for="file_name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.image.columns.file_name') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.file_name" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('file_name'), 'form-control-success': fields.file_name && fields.file_name.valid}" id="file_name" name="file_name" placeholder="{{ trans('admin.image.columns.file_name') }}">
        <div v-if="errors.has('file_name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('file_name') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('original_file_name'), 'has-success': fields.original_file_name && fields.original_file_name.valid }">
    <label for="original_file_name" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.image.columns.original_file_name') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.original_file_name" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('original_file_name'), 'form-control-success': fields.original_file_name && fields.original_file_name.valid}" id="original_file_name" name="original_file_name" placeholder="{{ trans('admin.image.columns.original_file_name') }}">
        <div v-if="errors.has('original_file_name')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('original_file_name') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('salsah_date'), 'has-success': fields.salsah_date && fields.salsah_date.valid }">
    <label for="salsah_date" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.image.columns.salsah_date') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.salsah_date" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('salsah_date'), 'form-control-success': fields.salsah_date && fields.salsah_date.valid}" id="salsah_date" name="salsah_date" placeholder="{{ trans('admin.image.columns.salsah_date') }}">
        <div v-if="errors.has('salsah_date')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('salsah_date') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('sequence_number'), 'has-success': fields.sequence_number && fields.sequence_number.valid }">
    <label for="sequence_number" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.image.columns.sequence_number') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.sequence_number" v-validate="''" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('sequence_number'), 'form-control-success': fields.sequence_number && fields.sequence_number.valid}" id="sequence_number" name="sequence_number" placeholder="{{ trans('admin.image.columns.sequence_number') }}">
        <div v-if="errors.has('sequence_number')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('sequence_number') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('location_id'), 'has-success': fields.location && fields.location.valid }">
    <label for="location" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.image.columns.location') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.location" v-validate="'integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('location_id'), 'form-control-success': fields.location && fields.location.valid}" id="location" name="location" placeholder="{{ trans('admin.image.columns.location') }}">
        <div v-if="errors.has('location_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('location_id') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('collection'), 'has-success': fields.collection && fields.collection.valid }">
    <label for="collection" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.image.columns.collection') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.collection" v-validate="'integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('collection'), 'form-control-success': fields.collection && fields.collection.valid}" id="collection" name="collection" placeholder="{{ trans('admin.image.columns.collection') }}">
        <div v-if="errors.has('collection')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('collection') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('verso'), 'has-success': fields.verso && fields.verso.valid }">
    <label for="verso" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.image.columns.verso') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.verso" v-validate="'integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('verso'), 'form-control-success': fields.verso && fields.verso.valid}" id="verso" name="verso" placeholder="{{ trans('admin.image.columns.verso') }}">
        <div v-if="errors.has('verso')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('verso') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('objecttype'), 'has-success': fields.objecttype && fields.objecttype.valid }">
    <label for="objecttype" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.image.columns.objecttype') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.objecttype" v-validate="'integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('objecttype'), 'form-control-success': fields.objecttype && fields.objecttype.valid}" id="objecttype" name="objecttype" placeholder="{{ trans('admin.image.columns.objecttype') }}">
        <div v-if="errors.has('objecttype')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('objecttype') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('model'), 'has-success': fields.model && fields.model.valid }">
    <label for="model" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.image.columns.model') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.model" v-validate="'integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('model'), 'form-control-success': fields.model && fields.model.valid}" id="model" name="model" placeholder="{{ trans('admin.image.columns.model') }}">
        <div v-if="errors.has('model')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('model') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('format'), 'has-success': fields.format && fields.format.valid }">
    <label for="format" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.image.columns.format') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.format" v-validate="'integer'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('format'), 'form-control-success': fields.format && fields.format.valid}" id="format" name="format" placeholder="{{ trans('admin.image.columns.format') }}">
        <div v-if="errors.has('format')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('format') }}</div>
    </div>
</div>


