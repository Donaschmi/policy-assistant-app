<template>
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
                    <jet-button @click.prevent="playQuestion">
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
</template>

<script>
import JetButton from '@/Jetstream/Button'
import JetDialogModal from '@/Jetstream/DialogModal'
import CreateActorForm from "../Actors/CreateActorForm";
export default {
    props: ['tenant'],
    components: {
        JetButton
    },
    data(){
        return {
            questions: null,
            currQuestionIndex: 0,
            utterance: null,
            uri: location.protocol + '//' + location.hostname + ':12101',
            attempt: 0
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
        this.fetchQuestions()
    },
    mounted() {
        let recordDemo = document.createElement('script')
        recordDemo.setAttribute('src', '/js/AudioRecorder/RecorderDemo.js')
        document.head.appendChild(recordDemo)
    },
    methods: {
        async sendRecord(arg) {
            await axios.post(`${this.uri}/api/speech-to-intent?outputFormat=rhasspy`,
                arg.blob, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            ).then(res => {
                let question = this.questions[this.currQuestionIndex]
                console.log({res});
                if (question.actor){
                    if (res.data.intent.name === "AnswerYes") {
                        question.answer = question.actor
                    }
                    else if (res.data.intent.name === "AnswerNon") {
                        question.answer = "Non"
                    }
                    else{
                        this.askRepeat()
                        return;
                    }
                }
                else{
                    if (res.data.intent.name === question.actor_1.actor_type.intent){
                        question.answer = question.actor_1
                    }
                    else if (res.data.intent.name === question.actor_2.actor_type.intent){
                        question.answer = question.actor_2
                    }
                    else {
                        this.askRepeat()
                        return;
                    }
                }
                this.currQuestionIndex++
                this.attempt = 0
                this.playQuestion()
            }).catch(err => {
                console.error({err});
            });
        },
        async fetchQuestions() {
            axios.get(`/users/${this.tenant.id}/questions?question_type=best`)
                .then(response => {
                    this.questions = response.data
                }).catch(e => {
                if (e.response.data === "actor_missing"){
                    this.error = "Veuillez créer au moins un acteur au préalable."
                }
                else if (e.response.data === "event_missing"){
                    this.error = "Vous couvrez déjà tous les évenements disponibles."
                }
            })
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
        playQuestion() {
            this.utterance.text = this.questions[this.currQuestionIndex].sentence
            speechSynthesis.speak(this.utterance);
        },
        askRepeat() {
            this.attempt++
            if (this.attempt === 3) {
                this.repeatQuestion()
                return;
            }
            this.utterance.text = "Je n'ai pas bien compris votre réponse, pouvez-vous répéter?"
            speechSynthesis.speak(this.utterance);
        },
        repeatQuestion(){
            this.utterance.text = "Je vais répéter la question."
            speechSynthesis.speak(this.utterance);
            this.attempt = 0
            this.playQuestion()
        }
    },
    watch: {
        currQuestionIndex: function(oldValue, newValue) {
            if (newValue === this.questions.length -1)
                this.submit()
        }
    }
}
</script>
