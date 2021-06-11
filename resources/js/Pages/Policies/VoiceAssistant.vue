<template>
    <app-layout>
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ tenant.name }}'s recordings
                </h2>
            </template>
        <div class="flex flex-col px-60 pt-6">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="border-t border-gray-200">
                <div class="">
                    <div class="">
                        <label class=" control-label">Audio input</label>
                        <div class="">
                            <select id="audio-in-select" class="form-control"><option value="no-input" selected="">(No input)</option><option value="xuRjdxAJ0z0FAwtXvO89ur/MqdngIyrVBV4TNUcFRSo=">Audio in 1</option></select>
                        </div>
                        <div class="">
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

                        <div class="col-sm-3">
                            <jet-button @click.prevent="startAssistant">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-play" viewBox="0 0 16 16">
                                    <path d="M10.804 8 5 4.633v6.734L10.804 8zm.792-.696a.802.802 0 0 1 0 1.392l-6.363 3.692C4.713 12.69 4 12.345 4 11.692V4.308c0-.653.713-.998 1.233-.696l6.363 3.692z"/>
                                </svg>
                            </jet-button>
                        </div>
                        <div class="m-auto">
                            <button style="justify-content: center;display: flex; margin:auto"

                                    id="record" :class="this.recordClasses" @click="toggleRecord">{{this.recordText}}</button>
                        </div>
                        <div class="col-sm-3 control-label"><span id="recording" class="text-danger hidden"><strong>RECORDING</strong></span>&nbsp; <span id="time-display">00:00</span></div>
                        <div class="col-sm-6 hidden"><span id="date-time" class="text-info">Wed Apr 21 2021 11:30:19 GMT+0200 (heure d’été d’Europe centrale)</span></div>
                    </div>
                </div>
                <div id="recording-list hidden"></div>
            </div>
        </div>
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
            step: "",
            recordText: "RECORD",
            recordClasses: "btn btn-danger",
            text: ""
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
                console.log(res.data.raw_text)
                this.treatAnswer(res)
            }).catch(err => {
            });
        },
        treatAnswer(res){
            switch (this.step){
                case config.START:
                    if (res.data.intent.name === "AnswerYes"){
                        this.text = "Très bien. Voici la question."
                        this.askQuestion(true)
                    }else if(res.data.intent.name === "AnswerNo"){
                        this.step = config.DONE
                    }
                    else {
                        this.askRepeat()
                    }
                    return
                case config.ASKED:
                    if (this.question.actor){
                        if (res.data.intent.name === "AnswerYes") {
                            this.question.answer = [this.question.actor]
                        }
                        else if (res.data.intent.name === "AnswerNo") {
                            this.question.answer = "Non"
                            retur;
                        }
                        else{
                            this.askRepeat()
                        }
                        this.askConfirmation()
                        return;
                    }
                    else {
                        if (res.data.intent.name === "AnswerYes") {
                            this.askPrecision(this.question.actor_1, this.question.actor_2)
                            return;
                        }
                        if (res.data.intent.name === "AnswerNo"){
                            this.askAlternative('actor')
                            return;
                        }
                        if (res.data.intent.name === this.question.actor_1.actor_type.intent) {
                            this.question.answer = [this.question.actor_1]
                        } else if (res.data.intent.name === this.question.actor_2.actor_type.intent) {
                            this.question.answer = [this.question.actor_2]
                        } else if (res.data.intent.name === "AnswerBoth" || res.data.intent.name === "AnswerYes") {
                            this.question.answer = [this.question.actor_1, this.question.actor_2]
                        } else {
                            this.askRepeat()
                            return;
                        }
                        this.askConfirmation()
                        return;
                    }
                case config.CONFIRM:
                    if (res.data.intent.name === "AnswerYes"){
                        this.submit()
                        this.askAnother()
                    }
                    else if(res.data.intent.name === "AnswerNo"){
                        this.askQuestion()
                    }
                    else{
                        this.askRepeat()
                    }
                    return;
                case config.ANOTHER:
                    if (res.data.intent.name === "AnswerYes"){
                        this.text = "Très bien. Voici la question."
                        this.askQuestion(true)
                    }
                    else if (res.data.intent.name === "AnswerNo"){
                       this.step = config.DONE
                    }
                    else{
                        this.askRepeat()
                    }
                    return;
                case config.ASK_ANOTHER_ACTION:
                    if (res.data.intent.name === "AnswerYes"){
                        this.askAction()
                    }
                    else if (res.data.intent.name === "AnswerNo"){
                        this.askAlternative('actor')
                    }
                    else{
                        this.askRepeat()
                    }
                    return;
                case config.ASK_ANOTHER_ACTOR:
                    if (res.data.intent.name === "AnswerYes"){
                        this.askActor()
                    }
                    else if (res.data.intent.name === "AnswerNo"){
                        this.askSkip()
                    }
                    else{
                        this.askRepeat()
                    }
                    return;
                case config.ANOTHER_ACTION:
                    if (res.data.intent.name === "AnswerYes"){
                        this.text = "Très bien. Voici la question."
                        this.askQuestion()
                    }
                    else if (res.data.intent.name === "AnswerNo"){
                        this.step = config.DONE
                    }
                    else{
                        this.askRepeat()
                    }
                    return;
                case config.ANOTHER_ACTOR:
                    if (res.data.intent.name === "AnswerYes"){
                        this.text = "Très bien. Voici la question."
                        this.askQuestion()
                    }
                    else if (res.data.intent.name === "AnswerNo"){
                        this.step = config.DONE
                    }
                    else{
                        this.askRepeat()
                    }
                    return;
                case config.ALTERNATIVE:
                    if (res.data.intent.name === "AnswerYes"){
                        return;
                    }
                    else if (res.data.intent.name === "AnswerNo"){

                    }
                    break;
                case config.DONE:
                    break;
            }
        },
        async startAssistant() {
            this.text = "Bonjour. Êtes-vous disposé à repondre à quelques questions?"
            this.step = config.START;
        },
        async askQuestion(fetch = false) {
            if (fetch){
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
            }
            this.step = config.ASKED
            this.text = this.question.question
        },
        askRepeat() {
            this.attempt++
            if (this.attempt === 3 && this.step === config.ASKED) {
                this.repeatQuestion()
                return;
            }
            this.text = "Je n'ai pas bien compris votre réponse, pouvez-vous répéter?"
        },
        askPrecision(actor1, actor2) {
            this.text = `Voulez-vous que je prévienne ${actor1.actor_type.sentence} ou ${actor2.actor_type.sentence} ou les deux?`
        },
        askConfirmation() {
            if (this.question.answer.length < 1){
                return;
            }
            this.step = config.CONFIRM
            let actorSentence = this.question.answer[0].actor_type.sentence
            if(this.question.answer.length === 2){
                actorSentence += " et " + this.question.answer[1].actor_type.sentence
            }
            this.text = `Très bien, je vais prévenir ${actorSentence} si ${this.question.sentence}. Pouvez-vous confirmer? Dites oui ou non.`
        },
        askAlternative(type) {
            if (type === 'action'){
                this.step = config.ASK_ANOTHER_ACTION
                this.text = "Voulez vous que j'effectue une autre action?"
            }
            else if(type === 'actor'){
                this.step = config.ASK_ANOTHER_ACTOR
                this.text = "Voulez vous que je prévienne une autre personne?"
            }
        },
        async askActor() {
            let actors = this.question.actor ? this.question.actor.id : this.question.actor_1.id +','+this.question.actor_2.id

            await axios.get(`/users/${this.tenant.id}/otherActors?event_id=${this.question.id}&actors=${actors}`).then(res => {
                if (res.data.length === 0) {
                    this.step = config.ANOTHER
                    this.text = "Je n'ai pas trouvé de personnes supplémentaires à prévenir. Voulez-vous que je pose une autre question?"
                }
                else if (res.data.length === 1) {
                    this.question.actor = res.data[0]
                    this.step = config.ASKED
                    this.text = `Dois-je prévenir ${this.question.actor.actor_type.sentence}?`
                }
                else {
                    this.question.actor_1 = res.data[0]
                    this.question.actor_2 = res.data[1]
                    this.step = config.ASKED
                    this.text = `Dois-je prévenir ${this.question.actor_1.actor_type.sentence} ou ${this.question.actor_2.actor_type.sentence}?`
                }
            }).catch(err => {
                console.log({err})
            });
        },
        async askAction() {
            await axios.get(`/`).then(res => {
                if (res.data.length === 0) {
                    this.step = config.ANOTHER
                    this.text = "Je n'ai pas trouvé de personnes supplémentaires à prévenir. Voulez-vous que je pose une autre question?"
                }
                else if (res.data.length === 1) {
                    this.question.actor = res.data[0]
                    this.askQuestion()
                }
                else {
                    this.question.actor_1 = res.data[0]
                    this.question.actor_2 = res.data[1]
                    this.askQuestion()
                }
            }).catch(err => {
            });
        },
        askSkip() {

        },
        askAnother() {
            this.step = config.ANOTHER
            this.text = `Votre nouvelle rêgle de sécurité a bien été enregistrée. Voulez-vous en créer une autre?`
        },
        repeatQuestion(){
            this.text = "Je vais répéter la question."
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
        },
        toggleRecord() {
            if (this.recordText === "RECORD"){
                this.recordText = "STOP"
                this.recordClasses = "btn btn-success"
            }
            else{
                this.recordText = "RECORD"
                this.recordClasses = "btn btn-danger"
            }
        }
    },
    watch: {
        step(){
            if (this.step === config.DONE){
                this.text = "Merci pour votre aide. Je vais prévenir vos proches des changements que vous avez éffectués."
            }
        },
        text: function(){
            console.log(this.text)
            this.utterance.text = this.text
            speechSynthesis.speak(this.utterance);
        }

    }
}
</script>
