import {reactive, ref} from 'vue'

/*
  This code is based on the blog post:
  "How To Make A Drag-and-Drop File Uploader With Vue.js 3"
  Author: Joseph Zimmerman
  URL: https://www.smashingmagazine.com/2022/03/drag-drop-file-uploader-vuejs-3/
*/
export default function ({emitter, addFilesEvent= [], removeFileEvent = []}) {
    const files = ref([])
    function addFiles(newFiles, event) {
        let newUploadAbleFiles = [...newFiles]
            .map((file) => new UploadAbleFile(file))
            .filter((file) => !fileExists(file.id))
        files.value = files.value.concat(newUploadAbleFiles)
        emitEvents(addFilesEvent, files.value.map(({ file }) => file));
    }

    function fileExists(otherId) {
        return files.value.some(({ id }) => id === otherId)
    }

    function removeFile(file) {
        const index = files.value.indexOf(file)

        if (index > -1) files.value.splice(index, 1)
        emitEvents(removeFileEvent, files.value.map(({ file }) => file));
    }

    function emitEvents(events, payload) {
        if (events && Array.isArray(events)) {
            events.forEach((event) => {
                emitter(event, payload);
            });
        }
    }

    return { files, addFiles, removeFile }
}

class UploadAbleFile {
    constructor(file) {
        this.file = file
        this.id = `${file.name}-${file.size}-${file.lastModified}-${file.type}`
        this.url = URL.createObjectURL(file)
        this.status = null
    }
}
