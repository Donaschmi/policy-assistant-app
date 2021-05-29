<template>
    <app-layout>
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ tenant.name }}'s recordings
                </h2>
            </template>

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
                        <jet-button @click.prevent="startAssistant">
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
    </app-layout>
</template>

<script>
import JetButton from '@/Jetstream/Button'
import AppLayout from '@/Layouts/AppLayout'
import {config} from '../../utils/dialogue'
export default {
    props: ['tenant'],
    components: {
        JetButton,
        AppLayout
    },
    data(){
        return {
            question: null,
            utterance: null,
            uri: location.protocol + '//' + location.hostname + ':12101',
            attempt: 0,
            lastSentence: "",
            answered: false,
            response: null,
            step: ""
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
            await axios.post(`${this.uri}/api/speech-to-intent?outputFormat=rhasspy`,
                arg.blob, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            ).then(res => {
                this.treatAnswer(res)
            }).catch(err => {
                console.error({err});
            });
        },
        treatAnswer(res){
            switch (this.step){
                case config.START:
                    if (res.data.intent.name === "AnswerYes"){
                        this.askQuestion()
                        return;
                    }else if(res.data.intent.name === "AnswerNo"){
                        this.step = config.DONE
                        return;
                    }
                    break;
                case config.ASKED:
                    if (this.question.actor){
                        if (res.data.intent.name === "AnswerYes") {
                            this.question.answer = [this.question.actor]
                        }
                        else if (res.data.intent.name === "AnswerNo") {
                            this.question.answer = "Non"
                        }
                        else{
                            this.askRepeat()
                            return;
                        }
                    }
                    else {
                        if (res.data.intent.name === "AnswerYes") {
                            this.askPrecision(this.question.actor_1, this.question.actor_2)
                            return;
                        }
                        if (res.data.intent.name === "AnswerNo"){
                            return;
                        }
                        if (res.data.intent.name === this.question.actor_1.actor_type.intent) {
                            this.question.answer = [this.question.actor_1]
                        } else if (res.data.intent.name === this.question.actor_2.actor_type.intent) {
                            this.question.answer = [this.question.actor_2]
                        } else if (res.data.intent.name === "AnswerBoth") {
                            this.question.answer = [this.question.actor_1, this.question.actor_2]
                        } else {
                            this.askRepeat()
                            return;
                        }
                        this.askConfirmation(this.question.answer, this.question.event)
                        return;
                    }
                    break;
                case config.CONFIRM:
                    if (res.data.intent.name === "AnswerYes"){
                        this.submit()
                        this.askAnother()
                    }
                    else if(res.data.intent.name === "AnswerNo"){

                    }
                    else{
                        this.askRepeat()
                        return;
                    }
                    break;
                case config.DONE:
                    break;
            }
        },
        async startAssistant() {
            this.utterance.text = "Bonjour. Êtes-vous disposé à repondre à quelques questions?"
            speechSynthesis.speak(this.utterance);
            this.step = config.START;
        },
        async askQuestion() {
            this.utterance.text = "Très bien. Voici la question."
            speechSynthesis.speak(this.utterance);
            await axios.get(`/users/${this.tenant.id}/questions?question_type=best`)
                .then(response => {
                    this.question = response.data
                }).catch(e => {
                if (e.response.data === "actor_missing"){
                    this.error = "Veuillez créer au moins un acteur au préalable."
                }
                else if (e.response.data === "event_missing"){
                    this.error = "Vous couvrez déjà tous les évenements disponibles."
                }
            })
            this.step = config.ASKED
            this.utterance.text = this.question.sentence
            speechSynthesis.speak(this.utterance);
        },
        askRepeat() {
            this.attempt++
            if (this.attempt === 3 && this.step === config.ASKED) {
                this.repeatQuestion()
                return;
            }
            this.utterance.text = "Je n'ai pas bien compris votre réponse, pouvez-vous répéter?"
            speechSynthesis.speak(this.utterance);
        },
        askPrecision(actor1, actor2) {
            this.utterance.text = `Voulez-vous que je prévienne ${actor1.actor_type.sentence} ou ${actor2.actor_type.sentence} ou les deux?`
            speechSynthesis.speak(this.utterance);
        },
        askConfirmation(answer, event) {
            if (answer.length < 1){
                return;
            }
            this.step = config.CONFIRM
            let actorSentence = answer[0].sentence
            if(answer.length === 2){
                actorSentence += " et " + answer[1].sentence
            }
            this.utterance.text = `Très bien, je vais prévenir ${actorSentence} si ${event.sentence}. Dites 'Oui' pour confirmer.`
            speechSynthesis.speak(this.utterance);
        },
        askAnother() {
            this.step = config.ANOTHER
            this.utterance.text = `Votre nouvelle rêgle de sécurité a bien été enregistrée. Voulez-vous en créer une autre?`
            speechSynthesis.speak(this.utterance);
        },
        repeatQuestion(){
            this.utterance.text = "Je vais répéter la question."
            speechSynthesis.speak(this.utterance);
            this.attempt = 0
            this.playQuestion()
        },

        async submit(){
            let data = new FormData();
            let event = JSON.stringify(this.question)
            data.append('event', event)
            axios.post(`/users/${this.tenant.id}/question`, data)
                .then(response => {
                    this.emitter.emit('new-policies', {policies: response.data})
                }).catch(e => {
            })
        }
    }
}
</script>
