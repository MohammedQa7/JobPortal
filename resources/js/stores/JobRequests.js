import { defineStore } from 'pinia'

export const useJobRequestsStore = defineStore('jobRequestData', {
    state: () => ({
        job: null,
        userRequests: null,
        canSendRequest: null,
    }),
})