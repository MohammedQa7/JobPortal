<template>
    <div class="waveform-container">
        <div v-if="isRecording" class="recording-container">
            <!-- <div class="waveform-container bg-gray-100 h-16 mb-2 rounded-md" ref="waveformContainer"></div> -->
            <div class="waveform">
                <div v-for="(value, index) in dataArray" :key="index" class="bar" :style="{ height: `${value / 2}px` }">
                </div>
            </div>
            <button @click="stopRecording"
                class="p-2 bg-red-500 text-white rounded-full hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50"
                title="Stop Recording">
                <Square class="w-5 h-5" />
            </button>
        </div>
        <button v-else @click="startRecording"
            class="p-2 bg-green-500 text-white rounded-full hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50"
            title="Start Recording" :disabled="isLoading">
            <Mic v-if="!isLoading" class="w-5 h-5" />
            <Loader2 v-else class="w-5 h-5 animate-spin" />
        </button>
        <p v-if="statusMessage" :class="['mt-2 text-sm', { 'text-red-500': isError, 'text-green-500': !isError }]">
            {{ statusMessage }}
        </p>
        <div v-if="audioUrl" class="mt-4">
            <audio controls :src="audioUrl" class="w-full"></audio>
            <button @click="downloadAudio"
                class="mt-2 p-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Download Recording
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, nextTick, watch } from 'vue';
import { Mic, Square, Loader2 } from 'lucide-vue-next';

const isRecording = ref(false);
const isLoading = ref(false);
const statusMessage = ref('');
const isError = ref(false);
const waveformContainer = ref(null);
const audioUrl = ref(null);
let mediaRecorder = null;
let audioContext = null;
let analyser = null;
const dataArray = ref(null);
let animationId = ref(null);
let audioChunks = [];
let canvas = null;
let canvasCtx = null;


const startRecording = async () => {
    isLoading.value = true;
    statusMessage.value = 'Requesting microphone access...';
    isError.value = false;
    audioUrl.value = null;
    audioChunks = [];

    try {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });

        mediaRecorder = new MediaRecorder(stream);

        audioContext = new (window.AudioContext || window.webkitAudioContext)();

        analyser = audioContext.createAnalyser();
        analyser.fftSize = 256;

        const source = audioContext.createMediaStreamSource(stream);

        source.connect(analyser);


        const bufferLength = analyser.frequencyBinCount;
        dataArray.value = new Uint8Array(bufferLength);

        isRecording.value = true;
        statusMessage.value = 'Recording...';
        animateWaveform();
        mediaRecorder.ondataavailable = (event) => {
            audioChunks.push(event.data);
        };


        mediaRecorder.onstop = () => {
            const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
            audioUrl.value = URL.createObjectURL(audioBlob);
            statusMessage.value = 'Recording finished. You can now play or download the audio.';
        };

        mediaRecorder.onerror = (event) => {
            isError.value = true;
            statusMessage.value = `Recording error: ${event.error.message}`;
        };

        mediaRecorder.start();
    } catch (error) {
        console.error('Error starting recording:', error);
        isError.value = true;
        if (error.name === 'NotAllowedError') {
            statusMessage.value = 'Microphone access denied. Please allow microphone access and try again.';
        } else if (error.name === 'NotFoundError') {
            statusMessage.value = 'No microphone found. Please check your audio input devices and try again.';
        } else {
            statusMessage.value = `An error occurred while trying to start recording: ${error.message}`;
        }
    } finally {
        isLoading.value = false;
    }
};

const stopRecording = () => {
    if (mediaRecorder && mediaRecorder.state === 'recording') {
        mediaRecorder.stop();
        mediaRecorder.stream.getTracks().forEach(track => {
            track.stop();
        });
        isRecording.value = false;
        cancelAnimationFrame(animationId.value);
        if (waveformContainer.value && canvas) {
            waveformContainer.value.removeChild(canvas);
            canvas = null;
            canvasCtx = null;
        }
    }
};

const animateWaveform = () => {
    if (!isRecording.value) return;
    analyser.getByteFrequencyData(dataArray.value);

    // Request next animation frame
    animationId.value = requestAnimationFrame(animateWaveform);
    
};
const downloadAudio = () => {
    if (audioUrl.value) {
        const a = document.createElement('a');
        a.href = audioUrl.value;
        a.download = 'recording.wav';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }
};

onUnmounted(() => {
    if (isRecording.value) {
        stopRecording();
    }
    if (audioContext) {
    }
    if (audioUrl.value) {
        URL.revokeObjectURL(audioUrl.value);
    }
});
</script>

<style scoped>
.waveform-container {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.waveform {
    display: flex;
    gap: 2px;
    margin-bottom: 10px;
}

.bar {
    width: 5px;
    background-color: black !important;
    transition: height 0.1s;
}
</style>