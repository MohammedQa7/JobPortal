export function calculateBidFees(bid, tax, receivedAmount, deductedAmount) {
    receivedAmount = (bid - (bid * tax)).toFixed(2);
    deductedAmount = (receivedAmount - bid).toFixed(2);

    return {
        receivedAmount, deductedAmount
    }
}