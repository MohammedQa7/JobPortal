
import { defineStore } from 'pinia'

export const useProposalStore = defineStore('proposalData', {
    state: () => ({
        isLoading: false,
        isOuterLoading: false,
        proposal: null,
    }),
})