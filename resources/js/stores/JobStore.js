import { defineStore } from 'pinia'

export const useJobsStore = defineStore('jobsData', {
    state: () => ({
        isLoading: false,
    }),
})