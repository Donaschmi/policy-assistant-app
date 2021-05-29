<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ tenant.name + "'s actors" }}
            </h2>
        </template>

        <div class="flex flex-col px-60 pt-6">
            <form>
                <inertia-link :href="route('user.policies', {user: tenant.id})" class="btn btn-primary ml-6 ">
                    Policies
                </inertia-link>

            </form>
            <div class="flex flex-wrap -mx-3 mb-2">
                <div class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Audio input</label>
                        <div class="col-sm-3">
                            <select id="audio-in-select" class="form-control"><option value="no-input" selected="">(No input)</option><option value="xuRjdxAJ0z0FAwtXvO89ur/MqdngIyrVBV4TNUcFRSo=">Audio in 1</option></select>
                        </div>
                        <div class="col-sm-3">
                            <input id="audio-in-level" type="range" min="0" max="100" value="20" class="hidden">
                        </div>
                    </div>
                    <div class="form-group hidden">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-3">
                            <input id="echo-cancellation" type="checkbox"> Enable echo cancellation
                        </div>
                        <div class="col-sm-6 text-warning"><strong>Experimental:</strong> cancellation on/off may work on Chrome only.</div>
                    </div>
                    <div class="form-group hidden">
                        <label class="col-sm-3 control-label">Test tone</label>
                        <div class="col-sm-3"></div>
                        <div class="col-sm-3">
                            <input id="test-tone-level" type="range" min="0" max="100" value="0">
                        </div>
                    </div><br>
                    <div class="form-group hidden">
                        <label class="col-sm-3 control-label">Recording time limit</label>
                        <div class="col-sm-3">
                            <input id="time-limit" type="range" min="1" max="10" value="3">
                        </div>
                        <div id="time-limit-text" class="col-sm-6">3 minutes</div>
                    </div><br>
                    <div class="form-group hidden">
                        <label class="col-sm-3 control-label">Encoding</label>
                        <div class="col-sm-3 hidden">
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
                    <div class="form-group hidden">
                        <label class="col-sm-3 control-label">Encoding process</label>
                        <div class="col-sm-9">
                            <input type="radio" name="encoding-process" mode="background" checked=""> Encode on recording background
                        </div>
                    </div>
                    <div class="form-group hidden">
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
                    <div class="form-group hidden">
                        <label class="col-sm-3 control-label">Recording buffer size</label>
                        <div class="col-sm-2">
                            <input id="buffer-size" type="range" min="0" max="6">
                        </div>
                        <div id="buffer-size-text" class="col-sm-7">4096 (browser default)</div>
                    </div>
                    <div class="form-group hidden">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9 text-warning"><strong>Notice: </strong> recording becomes unstable if buffer size is below browser default.</div>
                    </div><br>
                    <div class="form-group">
                        <div class="col-sm-3 control-label"><span id="recording" class="text-danger hidden"><strong>RECORDING</strong></span>&nbsp; <span id="time-display">00:00</span></div>
                        <div class="col-sm-3">
                            <jet-button @click.prevent="playRecording">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-play" viewBox="0 0 16 16">
                                    <path d="M10.804 8 5 4.633v6.734L10.804 8zm.792-.696a.802.802 0 0 1 0 1.392l-6.363 3.692C4.713 12.69 4 12.345 4 11.692V4.308c0-.653.713-.998 1.233-.696l6.363 3.692z"/>
                                </svg>
                            </jet-button>
                        </div>
                        <div class="col-sm-3">
                            <button id="record" class="btn btn-danger">RECORD</button>
                            <button id="cancel" class="btn btn-default hidden">CANCEL</button>
                        </div>
                        <div class="col-sm-6 hidden"><span id="date-time" class="text-info">Wed Apr 21 2021 11:30:19 GMT+0200 (heure d’été d’Europe centrale)</span></div>
                    </div>
                </div>
                <div id="recording-list hidden"></div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import JetButton from '@/Jetstream/Button'
import AppLayout from '@/Layouts/AppLayout'
import Swal from "sweetalert2";
export default {
    props: ['tenant'],
    components: {
        JetButton,
        AppLayout
    },
    data(){
        return {
            sentences: [
                {
                    sentence: "Oui",
                    intent: "AnswerYes"
                },
                {
                    sentence: "Non",
                    intent: "AnswerNo"
                },
                {
                    sentence: "Tous les deux",
                    intent: "AnswerBoth"
                },
                {
                    sentence: "mon fils",
                    intent: "AnswerSon"
                },
                {
                    sentence: "ma fille",
                    intent: "AnswerDaughter"
                },
                {
                    sentence: "mon voisin",
                    intent: "AnswerNeighbour"
                },
                {
                    sentence: "la police",
                    intent: "AnswerPolice"
                },
                {
                    sentence: "les pompiers",
                    intent: "AnswerFirefighter"
                },
                {
                    sentence: "mon aide-soignant",
                    intent: "AnswerCarer"
                },
            ],
            currSentenceIndex: 0,
            utterance: null,
            uri: location.protocol + '//' + location.hostname + ':12101',
        }
    },
    computed: {
    },
    created(){
        const eventBus = window.eventBus;
        const recordTopic = eventBus.getTopic('record');
        recordTopic.addListener('saved', this.sendRecord);
        this.utterance = new SpeechSynthesisUtterance()
        this.utterance.lang = 'fr-FR'
    },
    mounted() {
        let recordDemo = document.createElement('script')
        recordDemo.setAttribute('src', '/js/AudioRecorder/RecorderDemo.js')
        document.head.appendChild(recordDemo)
    },
    methods: {
        async sendRecord(arg) {
            let shouldBeSent = true;
            await Swal.fire({
                title: 'Valider?',
                showDenyButton: true,
                confirmButtonText: `Sauvegarder`,
                denyButtonText: `Recommencer`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {

                } else if (result.isDenied) {
                   shouldBeSent = false
                }
            })
            if (!shouldBeSent)
                return;
            let data = new FormData();
            data.append('intent', this.sentences[this.currSentenceIndex].intent)
            data.append('recording', arg.blob)
            await axios.post(`/users/${this.tenant.id}/recording`,
                data,{
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            ).then(res => {
                console.log(res)
                this.currSentenceIndex++
            }).catch(err => {
                console.error({err});
            });
        },
        async submit() {
            let data = new FormData();
            let events = JSON.stringify(this.questions)
            data.append('events', events)
            axios.post(`/users/${this.tenant.id}/questions`, data)
                .then(response => {
                    this.emitter.emit('new-policies', {policies: response.data})
                }).catch(e => {
            })
        },
        playRecording() {
            this.utterance.text = this.sentences[this.currSentenceIndex].sentence
            speechSynthesis.speak(this.utterance);
        },
    },
    watch: {
        currSentenceIndex(oldValue, newValue) {
            if (this.currSentenceIndex === this.sentences.length - 1){

            }
        }

    }
}
</script>
