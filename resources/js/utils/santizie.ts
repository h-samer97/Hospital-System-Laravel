export function sanitize(input: string) : string {
  return input.replace(/&/g, '&amp;')
          .replace(/</g, '$lt;')
          .replace(/>/g, '$gt;')
          .replace(/"/g, '$quot')
          .replace(/'/g, '$#x27')
          .replace(/\//g, '$#x2F')
}
export function sanitizeAndTrim(input: string) : string {
    return sanitize(input.trim());
}
export function sanitizeAndAmount(input: string) : string {
    return input.replace(/[^0-9.]/g, '');
}
