import React, { useState, useCallback } from "react";
import styles from "./GroupFormModal.module.css";
import { Service, GroupItem, GroupFormData } from "./types";
import { useForm } from "@inertiajs/react";

interface Props {
  onSuccess: () => void;
  services: Service[];
  urlStore: string;
}

const emptyItem = (): GroupItem => ({
  service_id:   '',
  quantity:     1,
  is_saved:     false,
  service_name: '',
  unit_price:   0,
});

const GroupFormModal: React.FC<Props> = ({ urlStore, services, onSuccess }) => {

  const [items, setItems]       = useState<GroupItem[]>([emptyItem()]);
  const [discount, setDiscount] = useState<number>(0);
  const [tax, setTax]           = useState<number>(17);
  const [name, setName]         = useState<string>('');
  const [notes, setNotes]       = useState<string>('');
  const [logError, setLogError] = useState<string | null>('');
  
  const { post, errors, processing, setData } = useForm<GroupFormData>();

  // ======= حساب الإجماليات =======
  const subTotal      = items.filter(i => i.is_saved).reduce((sum, item) => sum + (item.unit_price * item.quantity), 0);
  const afterDiscount = subTotal - (discount || 0);
  const total         = afterDiscount * (1 + (tax || 0) / 100);

  // ======= إضافة خدمة جديدة =======
  const addService = () => {
    const unSavedItem = items.some(i => !i.is_saved);
    if (unSavedItem) {
      setLogError('Please save all items before adding a new one');
      return;
    }
    setLogError(null);
    setItems([...items, emptyItem()]);
  };

  // ======= تأكيد وحفظ الصف محلياً =======
  const saveService = (index: number) => {
    const itemSelected = items[index];
    const service = services.find(s => s.id === Number(itemSelected.service_id));

    if (!service) return;

    setItems(prev => prev.map((it, i) =>
      i === index
        ? { ...it, is_saved: true, service_name: service.name, unit_price: Number(service.price) }
        : it
    ));
    setLogError(null);
  };

  // ======= تعديل الصف =======
  function editService(index: number) {
    const hasUnsaved = items.some((it, i) => i !== index && !it.is_saved);
    if (hasUnsaved) {
      setLogError('Please confirm all rows before editing.');
      return;
    }
    setItems(prev => prev.map((it, i) =>
      i === index ? { ...it, is_saved: false } : it
    ));
  }

  // ======= حذف الصف =======
  function removeService(index: number) {
    setItems(prev => prev.filter((_, i) => i !== index));
    setLogError(null);
  }

  // ======= التحديث اللحظي لحقول الصف =======
  const updateItem = useCallback((index: number, field: keyof GroupItem, value: any) => {
    setItems(prev => prev.map((it, i) =>
      i === index ? { ...it, [field]: value } : it
    ));
  }, []);

  // ======= الحفظ النهائي وإرسال البيانات لـ Laravel =======
  // ======= الحفظ النهائي وإرسال البيانات لـ Laravel =======
  function handleSubmit(e: React.FormEvent) {
    e.preventDefault();

    const savedItems = items.filter(i => i.is_saved);
    if (savedItems.length === 0) {
      setLogError('Please add and confirm at least one service.');
      return;
    }

    // 1. تجميع البيانات وضخها داخل الـ Form State الخاص بـ Inertia
    // الـ TypeScript هنا سيكون سعيداً جداً لأن الحقول تطابق الـ GroupFormData
    setData({
      name,
      notes,
      discount,
      tax_percent: tax,
      items: savedItems.map(i => ({
        service_id: Number(i.service_id),
        quantity:   i.quantity,
      })),
    });

    // 2. إرسال الطلب بمعاملين اثنين فقط وبدون أي خصائص غريبة ومرفوضة
    post(urlStore, {
      onSuccess,
    });
  }

  return (
    <div className={styles.formCard}>
      <h2 className={styles.formTitle}>Create New Group</h2>

      <form onSubmit={handleSubmit}>

        {/* اسم المجموعة + ملاحظات */}
        <div className={styles.row}>
          <div className={styles.formGroup}>
            <label>Group Name</label>
            <input type="text" value={name}
              onChange={(e) => setName(e.target.value)}
              className={errors.name ? styles.inputError : styles.input}
              placeholder="e.g. Basic Package" />
            {errors.name && <span className={styles.errorMsg}>{errors.name}</span>}
          </div>
          <div className={styles.formGroup}>
            <label>Notes <small>(optional)</small></label>
            <input type="text" value={notes}
              onChange={(e) => setNotes(e.target.value)}
              className={styles.input}
              placeholder="Any extra notes..." />
          </div>
        </div>

        {/* جدول الخدمات الديناميكي */}
        {logError && <p className={styles.rowError}>⚠️ {logError}</p>}

        <div className={styles.tableWrapper}>
          <table className={styles.table}>
            <thead>
              <tr>
                <th>#</th>
                <th>Service</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Line Total</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              {items.map((item, index) => (
                <tr key={index}
                  className={item.is_saved ? styles.rowSaved : styles.rowEditing}>

                  <td>{index + 1}</td>

                  {/* Service select أو نص بعد الحفظ */}
                  <td>
                    {item.is_saved ? (
                      <span className={styles.savedText}>
                        {item.service_name}
                      </span>
                    ) : (
                      <select
                        value={item.service_id}
                        onChange={(e) => updateItem(index, 'service_id', e.target.value)}
                        className={styles.select}
                      >
                        <option value="">-- Choose service --</option>
                        {services.map(s => (
                          <option key={s.id} value={s.id}>
                            {s.name} ({Number(s.price).toLocaleString()} EGP)
                          </option>
                        ))}
                      </select>
                    )}
                  </td>

                  {/* Unit Price */}
                  <td>
                    {item.is_saved
                      ? `${Number(item.unit_price).toLocaleString()} EGP`
                      : '—'}
                  </td>

                  {/* Quantity */}
                  <td>
                    {item.is_saved ? (
                      <span className={styles.savedText}>{item.quantity}</span>
                    ) : (
                      <input type="number"
                        value={item.quantity}
                        min={1}
                        onChange={(e) => updateItem(index, 'quantity', Number(e.target.value))}
                        className={styles.qtyInput}
                      />
                    )}
                  </td>

                  {/* Line Total */}
                  <td>
                    {item.is_saved
                      ? `${(item.unit_price * item.quantity).toLocaleString()} EGP`
                      : '—'}
                  </td>

                  {/* Actions */}
                  <td className={styles.rowActions}>
                    {item.is_saved ? (
                      <button type="button" className={styles.editBtn}
                        onClick={() => editService(index)}>
                        ✏️ Edit
                      </button>
                    ) : (
                      <button type="button" className={styles.confirmBtn}
                        disabled={!item.service_id}
                        onClick={() => saveService(index)}>
                        ✅ Confirm
                      </button>
                    )}
                    <button type="button" className={styles.removeBtn}
                      onClick={() => removeService(index)}>
                      🗑️
                    </button>
                  </td>

                </tr>
              ))}
            </tbody>
          </table>
        </div>

        <button type="button" className={styles.addRowBtn} onClick={addService}>
          + Add Service Row
        </button>

        {/* ===== Summary Table ===== */}
        <div className={styles.summary}>
          <div className={styles.summaryRow}>
            <span>Subtotal</span>
            <strong>{subTotal.toLocaleString()} EGP</strong>
          </div>

          <div className={styles.summaryRow}>
            <span>Discount</span>
            <input type="number" value={discount} min={0}
              onChange={(e) => setDiscount(Number(e.target.value))}
              className={styles.summaryInput} />
            <span>EGP</span>
          </div>

          <div className={styles.summaryRow}>
            <span>Tax</span>
            <input type="number" value={tax} min={0} max={100}
              onChange={(e) => setTax(Number(e.target.value))}
              className={styles.summaryInput} />
            <span>%</span>
          </div>

          <div className={`${styles.summaryRow} ${styles.totalRow}`}>
            <span>Total (after tax)</span>
            <strong>{total.toLocaleString(undefined, { maximumFractionDigits: 2 })} EGP</strong>
          </div>
        </div>

        <div className={styles.formFooter}>
          <button type="submit" className={styles.submitBtn} disabled={processing}>
            {processing ? 'Saving...' : '✔ Save Group'}
          </button>
        </div>

      </form>
    </div>
  );
};

export default GroupFormModal;