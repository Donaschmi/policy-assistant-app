<template>
    <div class="flex flex-wrap -mx-3 mb-2">
        <div class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-3 control-label">Audio input</label>
                <div class="col-sm-3">
                    <select id="audio-in-select" class="form-control"><option value="no-input" selected="">(No input)</option><option value="xuRjdxAJ0z0FAwtXvO89ur/MqdngIyrVBV4TNUcFRSo=">Audio in 1</option></select>
                </div>
                <div class="col-sm-3">
                    <input id="audio-in-level" type="range" min="0" max="100" value="0" class="hidden">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3"></div>
                <div class="col-sm-3">
                    <input id="echo-cancellation" type="checkbox"> Enable echo cancellation
                </div>
                <div class="col-sm-6 text-warning"><strong>Experimental:</strong> cancellation on/off may work on Chrome only.</div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Test tone</label>
                <div class="col-sm-3"></div>
                <div class="col-sm-3">
                    <input id="test-tone-level" type="range" min="0" max="100" value="0">
                </div>
            </div><br>
            <div class="form-group">
                <label class="col-sm-3 control-label">Recording time limit</label>
                <div class="col-sm-3">
                    <input id="time-limit" type="range" min="1" max="10" value="3">
                </div>
                <div id="time-limit-text" class="col-sm-6">3 minutes</div>
            </div><br>
            <div class="form-group">
                <label class="col-sm-3 control-label">Encoding</label>
                <div class="col-sm-3">
                    <input type="radio" name="encoding" encoding="wav" checked=""> .wav &nbsp;
                    <input type="radio" name="encoding" encoding="ogg"> .ogg &nbsp;
                    <input type="radio" name="encoding" encoding="mp3"> .mp3
                </div>
                <label id="encoding-option-label" class="col-sm-2 control-label"></label>
                <div class="col-sm-2">
                    <input id="encoding-option" type="range" min="0" max="11" value="6" class="hidden">
                </div>
                <div id="encoding-option-text" class="col-sm-2"></div>
            </div><br>
            <div class="form-group">
                <label class="col-sm-3 control-label">Encoding process</label>
                <div class="col-sm-9">
                    <input type="radio" name="encoding-process" mode="background" checked=""> Encode on recording background
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3"></div>
                <div class="col-sm-3">
                    <input type="radio" name="encoding-process" mode="separate"> Encode after recording (safer)
                </div>
                <label id="report-interval-label" class="col-sm-2 control-label hidden">Reports every</label>
                <div class="col-sm-2">
                    <input id="report-interval" type="range" min="1" max="5" value="1" class="hidden">
                </div>
                <div id="report-interval-text" class="col-sm-2 hidden">1 second</div>
            </div><br>
            <div class="form-group">
                <label class="col-sm-3 control-label">Recording buffer size</label>
                <div class="col-sm-2">
                    <input id="buffer-size" type="range" min="0" max="6">
                </div>
                <div id="buffer-size-text" class="col-sm-7">4096 (browser default)</div>
            </div>
            <div class="form-group">
                <div class="col-sm-3"></div>
                <div class="col-sm-9 text-warning"><strong>Notice: </strong> recording becomes unstable if buffer size is below browser default.</div>
            </div><br>
            <div class="form-group">
                <div class="col-sm-3 control-label"><span id="recording" class="text-danger hidden"><strong>RECORDING</strong></span>&nbsp; <span id="time-display">00:00</span></div>
                <div class="col-sm-3">
                    <button id="record" class="btn btn-danger">RECORD</button>
                    <button id="cancel" class="btn btn-default hidden">CANCEL</button>
                </div>
                <div class="col-sm-6"><span id="date-time" class="text-info">Wed Apr 21 2021 11:30:19 GMT+0200 (heure d’été d’Europe centrale)</span></div>
            </div>
        </div>
        <div id="recording-list"></div>
    </div>
</template>

<script>
import JetButton from '@/Jetstream/Button'
import JetDialogModal from '@/Jetstream/DialogModal'
import CreateActorForm from "../Actors/CreateActorForm";
export default {
    components: {
        JetButton
    },
    data(){
        return {
            audioRecord: null
        }
    },
    computed: {
      recording(){
          return this.audioRecord.isRecording()
      }
    },
    created(){

    },
    mounted() {
        let recordDemo = document.createElement('script')
        recordDemo.setAttribute('src', '/js/AudioRecorder/RecorderDemo.js')
        document.head.appendChild(recordDemo)
    },
    methods: {
        init(){
        },
        callback(msg) {
            console.debug('Event: ', msg)
        }
    }
}
</script>
