import styles from './InfoRow.module.css'

  interface Props {
      label?: string;
      value: string | number | null | undefined;
      mono?: boolean;
  }
const InfoRow = ({label, value, mono} : Props) => {

  return (
    <div className={styles.row}>
      <span className={styles.label}>{label}</span>
      <span className={`${styles.value} ${mono ? styles.mono : ''}`}>
        { value ?? 'N/A' }
      </span>
    </div>
  );

}

export default InfoRow;