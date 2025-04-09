import { useState } from "react";
import {router, useForm, usePage} from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { NumberInput } from "@/Components/Core/NumberInput";

// Define types
// interface InventoryItems {
//   id: number;
//   name: string;
//   inventory_id: number;
// }


interface InventoryItems {
  id: number;
  name: string;
  inventory_id: number;
  options: string[]; // e.g., ['size', 'weight']
  option_values: Record<string, OptionValue[]>; // FIXED type
  image?: string;
}

interface OptionValue {
  value: string;
  custom_value: string | null;
}

interface Inventory {
  id: number;
  name: string;
}

interface Step4Props {
  inquiry: { id: number };
  categories: Inventory[];
  inventoryItems: InventoryItems[];
}

type InventoryItem = {
  category: string;
  item: string;
  quantity: number;
  size: string;
  weight: string;
  type: string;
  doors: string;
  "rear-walls": string;
};

type FormData = {
  inventory: InventoryItem[];
};


export default function Step4({ inquiry, categories, inventoryItems }: Step4Props) {




  const { data, setData, post, processing } = useForm<FormData>({
    inventory: [] as {
      category: string;
      item: string;
      quantity: number;
      size: string;
      weight: string;
      type: string;
      doors: string;
      "rear-walls": string;
    }[],
  });

  const [showPopup, setShowPopup] = useState<'category' | 'product' | 'details' | null>(null);
  const [selectedCategory, setSelectedCategory] = useState<number | null>(null);
  const [selectedProduct, setSelectedProduct] = useState<string | null>(null);
  const [newItem, setNewItem] = useState({
    category: "",
    item: "",
    quantity: 1,
    size: "",
    weight: "",
    type: "",
    doors: "",
    "rear-walls": "",
  });

  function openCategoryPopup() {
    setShowPopup("category");
  }

  function selectCategory(categoryId: number) {
    setSelectedCategory(categoryId);
    const categoryName = categories.find(cat => cat.id === categoryId)?.name || "";
    setNewItem(prev => ({ ...prev, category: categoryName }));
    setShowPopup("product");
  }

  function selectProduct(itemName: string) {
    setSelectedProduct(itemName);
    setShowPopup("details");
  }

  function addItem() {
    if (selectedProduct && newItem.quantity > 0) {
      setData("inventory", [...data.inventory, { ...newItem, item: selectedProduct }]);
      setNewItem({
        category: "",
        item: "",
        quantity: 1,
        size: "",
        weight: "",
        type: "",
        doors: "",
        "rear-walls": "",
      });
      setShowPopup(null);
      setSelectedCategory(null);
      setSelectedProduct(null);
    }
  }

  function removeItem(index: number) {
    setData("inventory", data.inventory.filter((_, i) => i !== index));
  }

  function submit(e: React.FormEvent) {
    e.preventDefault();
    post(route("inquiry.step4.store", { inquiry: inquiry.id }));
  }

  const groupedInventory = data.inventory.reduce((acc, item) => {
    if (!acc[item.category]) {
      acc[item.category] = [];
    }
    acc[item.category].push(item);
    return acc;
  }, {} as Record<string, typeof data.inventory>);


  const selectedItem = inventoryItems.find(item => item.name === selectedProduct);

  const optionValuesArray = selectedItem?.option_values ?? [];
  const optionValues = optionValuesArray.reduce((acc, curr) => {
    Object.entries(curr).forEach(([key, value]) => {
      acc[key] = value;
    });
    return acc;
  }, {});






  const productOptions = Object.keys(optionValues);


  //console.log(selectedItem);



  function getCustomLabel(
    key: string,
    value: string | undefined,
    optionValues: Record<string, OptionValue[]>
  ): string {
    const match = optionValues?.[key]?.find(opt => opt.value === value);
    return match?.custom_value || value!;
  }

  return (
    <AuthenticatedLayout>
      <div className="min-h-[calc(100vh-64px)] flex items-start">
        <div className="bg-white px-10 py-10 shadow-md sm:rounded-lg mt-6 mx-auto w-[500px]">
          <div className="text-center">
            <h1 className="text-3xl font-bold">Furniture to be transported</h1>
            <p>Specify your furniture and large items that do not fit into boxes</p>
          </div>

          <form onSubmit={submit}>
            <h1 className="text-xl font-semibold mt-6 mb-2">Step 4: Inventory Management</h1>

            <button type="button" className="btn btn-neutral btn-wide" onClick={openCategoryPopup}>
              Add Item
            </button>

            <ul className="mt-4 space-y-4">
              {/*{Object.entries(groupedInventory).map(([category, items]) => (*/}
              {/*  <li key={category}>*/}
              {/*    <h3 className="font-bold">{category}</h3>*/}
              {/*    <ul className="ml-4 list-disc">*/}
              {/*      {items.map((inv, index) => (*/}
              {/*        <li key={index}>*/}
              {/*          {inv.item} (x{inv.quantity}) –*/}
              {/*          {inv.size && `Size: ${getCustomLabel("size", inv.size, optionValues)}, `}*/}
              {/*          {inv.weight && `Weight: ${getCustomLabel("weight", inv.weight, optionValues)}, `}*/}
              {/*          {inv.type && `Type: ${getCustomLabel("type", inv.type, optionValues)}, `}*/}
              {/*          {inv.doors && `Doors: ${getCustomLabel("doors", inv.doors, optionValues)}, `}*/}
              {/*          {inv["rear-walls"] && `Rear Walls: ${getCustomLabel("rear-walls", inv["rear-walls"], optionValues)}`}*/}
              {/*          <button type="button" className="btn btn-error btn-sm ml-2" onClick={() => removeItem(index)}>*/}
              {/*            Remove*/}
              {/*          </button>*/}
              {/*        </li>*/}
              {/*      ))}*/}
              {/*    </ul>*/}
              {/*  </li>*/}
              {/*))}*/}
              {Object.entries(groupedInventory).map(([category, items]) => (
                <li key={category}>
                  <h3 className="font-bold">{category}</h3>
                  <ul className="ml-4 list-disc">
                    {items.map((inv, index) => (
                      <li key={index}>
                        {inv.item} (x{inv.quantity})
                        {Object.entries(inv).map(([key, value]) => {
                          if (["item", "quantity"].includes(key)) return null; // skip these
                          const label = getCustomLabel(key, String(value), optionValues);
                          return label ? ` – ${key.replace("option_", "").replace("-", " ")}: ${label}` : null;
                        })}
                        <button type="button" className="btn btn-error btn-sm ml-2" onClick={() => removeItem(index)}>
                          Remove
                        </button>
                      </li>
                    ))}
                  </ul>
                </li>
              ))}

            </ul>

            <div className="flex justify-end mt-6">
              <button className="btn btn-neutral" type="submit" disabled={processing}>
                Continue
              </button>
            </div>
          </form>

          {/* CATEGORY MODAL */}
          {showPopup === "category" && (
            <dialog className="modal modal-open">

              <div className="modal-box max-w-2xl">

                <button className="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" onClick={() => setShowPopup(null)}>X</button>

                <h2 className="text-xl font-bold">Select Room</h2>
                <div className="grid grid-cols-3 gap-2 mt-4">
                  {categories.map((category) => (
                    <button key={category.id} className="btn" onClick={() => selectCategory(category.id)}>
                      {category.name}
                    </button>
                  ))}
                </div>
                {/*<button className="btn btn-outline mt-4" onClick={() => setShowPopup(null)}>Close</button>*/}
              </div>
            </dialog>
          )}

          {/* PRODUCT MODAL */}
          {showPopup === "product" && selectedCategory !== null && (
            <dialog className="modal modal-open">
              <div className="modal-box max-w-2xl">
                <div className={"absolute right-2 top-2"}>
                  <button className="btn btn-sm text-xs uppercase btn-ghost mt-4" onClick={() => setShowPopup("category")}>
                    Back
                  </button>
                  <button className="btn btn-sm btn-circle btn-ghost "
                          onClick={() => setShowPopup(null)}>X
                  </button>
                </div>
                <h2 className="text-xl font-bold">Select Product</h2>
                <div className="grid grid-cols-3 gap-2 mt-4">
                  {inventoryItems
                    .filter((item) => item.inventory_id === selectedCategory)
                    .map((item) => (
                      <button key={item.id} className="btn btn-sm text-xs font-semibold" onClick={() => selectProduct(item.name)}>
                        {item.name}
                      </button>
                    ))}
                </div>

              </div>
            </dialog>
          )}

          {/* DETAILS MODAL */}
          {showPopup === "details" && selectedProduct && selectedItem && (
            <dialog className="modal modal-open">
              <div className="modal-box max-w-2xl">

                <div className={"absolute right-2 top-2"}>
                  <button className="btn btn-sm text-xs uppercase btn-ghost mt-4"
                          onClick={() => setShowPopup("product")}>
                    Back
                  </button>
                  <button className="btn btn-sm btn-circle btn-ghost "
                          onClick={() => setShowPopup(null)}>X
                  </button>
                </div>


                <h2 className="text-sm font-semibold capitalize mb-1">Quantiry</h2>

                <div className="w-32">
                  <NumberInput
                    type="number"
                    className="input input-bordered mt-2 w-32"
                    placeholder="Quantity"
                    min="1"
                    value={newItem.quantity}
                    onChange={(e) => setNewItem({...newItem, quantity: Number(e.target.value)})}
                  />
                </div>
                <div className="grid grid-cols-2 gap-4 mt-4">

                  {productOptions.map((optionKey) => {
                    const values = optionValues[optionKey];
                    if (!Array.isArray(values)) return null;

                    return (
                      <fieldset key={optionKey}>
                        <legend
                          className="text-sm font-semibold capitalize">{optionKey.replace("option_", "").replace("-", " ")}
                        </legend>
                        {values.map((val, idx) => (
                          <label key={idx} className="flex items-center gap-2 mt-1 mb-2 cursor-pointer">
                            <input
                              type="radio"
                              className="radio radio-sm"
                              name={optionKey}
                              value={val.value}
                              onChange={(e) => setNewItem({...newItem, [optionKey]: e.target.value})}
                            />
                            <span className="text-sm">{val.value}</span>
                            {val.custom_value && (
                              <span className="text-xs text-gray-500">({val.custom_value})</span>
                            )}
                          </label>
                        ))}
                      </fieldset>
                    );
                  })}
                </div>

                <div className="mt-6 flex justify-between">
                  <button className="btn btn-neutral" onClick={addItem}>
                    Add Item
                  </button>
                </div>
              </div>
            </dialog>
          )}

        </div>
      </div>
    </AuthenticatedLayout>
  );
}
