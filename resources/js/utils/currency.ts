export default function formatCurrency(amount: number | string) : string {

  const num = typeof amount === 'string' ? Number(amount) : amount;

  return `${Math.abs(num).toLocaleString('en-US', {
    maximumFractionDigits: 2,
    minimumFractionDigits:2
  })} EGP`;

}