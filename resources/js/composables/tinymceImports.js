// Import TinyMCE
import Editor from '@tinymce/tinymce-vue';
import tinymce from 'tinymce/tinymce';

// Default icons are required for TinyMCE 5.3 or above
import 'tinymce/icons/default';

// Required TinyMCE components
import 'tinymce/themes/silver';
import 'tinymce/models/dom';

// Import a skin (can be a custom skin instead of the default)
import 'tinymce/skins/ui/oxide/skin.css';

// Any plugins you want to use has to be imported
import 'tinymce/plugins/link';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/image';
import 'tinymce/plugins/anchor';
import 'tinymce/plugins/wordcount';
import 'tinymce/plugins/media';
import 'tinymce/plugins/autolink';
import 'tinymce/plugins/charmap';
import 'tinymce/plugins/codesample';
import 'tinymce/plugins/searchreplace';
import 'tinymce/plugins/table';
import 'tinymce/plugins/visualblocks';
import 'tinymce/plugins/fullscreen';
import 'tinymce/plugins/quickbars';

// Content UI CSS is required
import contentUiSkinCss from 'tinymce/skins/ui/oxide/content.css?inline';

// The default content CSS can be changed or replaced with appropriate CSS for the editor content.
import contentCss from 'tinymce/skins/content/default/content.css?inline';

export { Editor, tinymce, contentUiSkinCss, contentCss };