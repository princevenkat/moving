import {useState} from "react";
import {Link, router, useForm} from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import {NumberInput} from "@/Components/Core/NumberInput";

// Define types
interface InventoryItems {
  id: number;
  name: string;
  inventory_id: number;
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

export default function Step4({inquiry, categories, inventoryItems}: Step4Props) {
  const {data, setData, post, processing} = useForm({
    inventory: [] as {
      room: string;
      category: string;
      item: string;
      quantity: number;
      size: string;
      weight: string;
      type: string
    }[],
  });

  const [showPopup, setShowPopup] = useState<'category' | 'product' | 'details' | null>(null);
  const [selectedCategory, setSelectedCategory] = useState<number | null>(null);
  const [selectedProduct, setSelectedProduct] = useState<string | null>(null);
  const [newItem, setNewItem] = useState({
    room: "",
    category: "",
    item: "",
    quantity: 1,
    size: "",
    weight: "",
    type: ""
  });

  function openCategoryPopup() {
    setShowPopup("category");
  }

  function selectCategory(categoryId: number) {
    setSelectedCategory(categoryId);
    const categoryName = categories.find(cat => cat.id === categoryId)?.name || "";
    setNewItem(prev => ({...prev, category: categoryName}));
    setShowPopup("product");
  }

  function selectProduct(itemName: string) {
    setSelectedProduct(itemName);
    setShowPopup("details");
  }

  function addItem() {
    if (newItem.room.trim() && selectedProduct && newItem.quantity > 0) {
      setData("inventory", [...data.inventory, {...newItem, item: selectedProduct}]);
      setNewItem({room: "", category: "", item: "", quantity: 1, size: "", weight: "", type: ""});
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
    post(route("inquiry.step4.store", {inquiry: inquiry.id}));
  }

  const groupedInventory = data.inventory.reduce((acc, item) => {
    if (!acc[item.room]) {
      acc[item.room] = {};
    }
    if (!acc[item.room][item.category]) {
      acc[item.room][item.category] = [];
    }
    acc[item.room][item.category].push(item);
    return acc;
  }, {} as Record<string, Record<string, typeof data.inventory>>);


  const options = {
    type: ["Dismantlable", "Non dismantlable"],
    size: ["Up to 1.2m long", "Up to 1.8m long", "Up to 2.4m long"],
    weight: ["Up to 60kg", "60kg to 100kg", "100kg to 150kg", "More than 150kg"],
    "rear-walls": ["nailed rear wall", "non-nailed rear wall"],
    doors: ["Normal doors", "Sliding doors"],
  };

  const productOptions = {
    Couch: ["size"],
    Table: ["size", "weight"],
    Chair: ["weight"],
    Wardrobe: ["weight", "type", "doors", "rear-walls"],
  };
  return (
    <AuthenticatedLayout>
      <div className="min-h-[calc(100vh-64px)] flex items-start">
        <div className="bg-white px-10 py-10 shadow-md sm:rounded-lg  mt-6 mx-auto w-[500px]">
          <div>
            <div className='text-center'>
              <h1 className="text-3xl font-bold ">Furniture to be transported</h1>
              <p className="">Specify your furniture and large items that do not fit into boxes in each room</p>
            </div>
          </div>
          <div>
            <h1>Step 4: Inventory Management</h1>
            <form onSubmit={submit}>
              <button type="button" className="btn btn-neutral btn-wide" onClick={openCategoryPopup}>Add Room</button>

              <ul>
                {Object.entries(groupedInventory).map(([room, categories]) => (
                  <li key={room}>
                    {Object.entries(categories).map(([category, items]) => (
                      <div key={category}>
                        <h3>{category}</h3>
                        <ul>
                          {items.map((inv, index) => (
                            <li key={index}>
                              {inv.item} (x{inv.quantity}) - Size: {inv.size} - Weight: {inv.weight} - Type: {inv.type}
                              <button type="button" className="btn btn-error" onClick={() => removeItem(index)}>Remove
                              </button>
                            </li>
                          ))}
                        </ul>
                      </div>
                    ))}
                  </li>
                ))}
              </ul>

              {/*<button type="submit" disabled={processing}>Next</button>*/}

              <div className="flex justify-between mt-6">
                {/*<button type="button" onClick={() => router.visit(route("inquiry.start"))} className="btn ">*/}
                {/*  Back*/}
                {/*</button>*/}
                <button className='btn btn-neutral' type="submit" disabled={processing}>Continue</button>
              </div>

            </form>

            {/* Category Selection Modal */}
            {showPopup === "category" && (
              <dialog id="category_modal" className="modal modal-open">
                <div className="modal-box max-w-2xl">
                  <h2 className="text-xl font-bold">Select Inventory Category</h2>
                  <div className="grid grid-cols-3 gap-2 mt-4">
                    {categories.map((category) => (
                      <button key={category.id} className="btn " onClick={() => selectCategory(category.id)}>
                        {category.name}
                      </button>
                    ))}
                  </div>
                  <button className="btn btn-outline mt-4" onClick={() => setShowPopup(null)}>Close</button>
                </div>
              </dialog>
            )}

            {/* Product Selection Modal */}
            {showPopup === "product" && selectedCategory !== null && (
              <dialog id="product_modal" className="modal modal-open">
                <div className="modal-box max-w-2xl">
                  <h2 className="text-xl font-bold">Select Product</h2>
                  <div className="grid grid-cols-3 gap-2 mt-4">
                    {inventoryItems
                      .filter((item) => item.inventory_id === selectedCategory)
                      .map((item) => (
                        <button key={item.id} className="btn " onClick={() => selectProduct(item.name)}>
                          {item.name}
                        </button>
                      ))}
                  </div>
                  <button className="btn btn-outline mt-4" onClick={() => setShowPopup("category")}>Back</button>
                </div>
              </dialog>
            )}

            {/* Product Details Input Modal */}
            {showPopup === "details" && selectedProduct !== null && (
              <dialog id="details_modal" className="modal modal-open">
                <div className="modal-box max-w-2xl">
                  <h2 className="text-xl font-bold">Enter Product Details</h2>
                  <div className="max-w-40 my-3">
                    <NumberInput type="number" className="input input-bordered mt-2" placeholder="Quantity" min="1"
                                 value={newItem.quantity}
                                 onChange={(e) => setNewItem({...newItem, quantity: Number(e.target.value)})}/>
                  </div>

                  <div className="grid grid-cols-3 gap-2 mt-4">


                    {Object.entries(options).map(([category, values]) => {
                      console.log("Selected Product:", selectedProduct); // Debugging

                      // Get the applicable options for the selected product
                      const applicableOptions = productOptions[selectedProduct as keyof typeof productOptions] || [];

                      // If the current category is not in the applicable options, hide it
                      if (!applicableOptions.includes(category)) {
                        console.log(`Hiding ${category} for:`, selectedProduct); // Debugging
                        return null;
                      }

                      return (
                        <fieldset key={category} className="mt-3">
                          <legend className="text-sm font-semibold">{category.replace("-", " ").toUpperCase()}</legend>
                          {values.map((value) => (
                            <label key={value} className="flex items-center gap-2 mt-2">
                              <input
                                type="radio"
                                className="radio radio-sm"
                                name={category}
                                value={value}
                                onChange={(e) => setNewItem({...newItem, [category]: e.target.value})}
                              />
                              <span className="text-sm">{value}</span>
                            </label>
                          ))}
                        </fieldset>
                      );
                    })}

                  </div>

                  <div className="mt-6 mb-2">
                    <textarea className="textarea textarea-neutral w-full h-32 border-gray-300"
                              placeholder="Special features"></textarea>
                  </div>


                  {/*<div className="grid grid-cols-3 gap-2 mt-4">*/}
                  {/*  /!* TYPE *!/*/}
                  {/*  <fieldset className="mt-3">*/}
                  {/*    <legend className="text-sm font-semibold">Type</legend>*/}
                  {/*    <label className="flex items-center gap-2 mt-2">*/}
                  {/*      <input type="radio" className="radio radio-sm" name="type" value="Dismantlable" onChange={(e) => setNewItem({ ...newItem, type: e.target.value })} /> <span className="text-sm">Dismantlable</span>*/}
                  {/*    </label>*/}
                  {/*    <label className="flex  items-center gap-2 mt-2">*/}
                  {/*      <input type="radio" className="radio radio-sm" name="type" value="Non dismantlable" onChange={(e) => setNewItem({ ...newItem, type: e.target.value })} /> <span className="text-sm">Non dismantlable</span>*/}
                  {/*    </label>*/}
                  {/*  </fieldset>*/}

                  {/*  /!* SIZE *!/*/}
                  {/*  <fieldset className="mt-3">*/}
                  {/*    <legend className="text-sm font-semibold">Size</legend>*/}
                  {/*    <label className="flex items-center gap-2 mt-2">*/}
                  {/*      <input type="radio" className="radio radio-sm" name="size" value="Up to 1.2m long" onChange={(e) => setNewItem({ ...newItem, type: e.target.value })} /> <span className="text-sm">Up to 1.2m long</span>*/}
                  {/*    </label>*/}
                  {/*    <label className="flex  items-center gap-2 mt-2">*/}
                  {/*      <input type="radio" className="radio radio-sm" name="size" value="Up to 1.8m long" onChange={(e) => setNewItem({ ...newItem, type: e.target.value })} /> <span className="text-sm">Up to 1.8m long</span>*/}
                  {/*    </label>*/}
                  {/*    <label className="flex  items-center gap-2 mt-2">*/}
                  {/*      <input type="radio" className="radio radio-sm" name="size" value="Up to 2.4m long" onChange={(e) => setNewItem({ ...newItem, type: e.target.value })} /> <span className="text-sm">Up to 2.4m long</span>*/}
                  {/*    </label>*/}
                  {/*  </fieldset>*/}

                  {/*  /!* WEIGHT *!/*/}
                  {/*  <fieldset className="mt-3">*/}
                  {/*    <legend className="text-sm font-semibold">Weight</legend>*/}
                  {/*    <label className="flex items-center gap-2 mt-2">*/}
                  {/*      <input type="radio" className="radio radio-sm" name="weight" value="Up to 60kg" onChange={(e) => setNewItem({ ...newItem, type: e.target.value })} /> <span className="text-sm">Up to 60kg</span>*/}
                  {/*    </label>*/}
                  {/*    <label className="flex  items-center gap-2 mt-2">*/}
                  {/*      <input type="radio" className="radio radio-sm" name="weight" value="60kg to 100kg" onChange={(e) => setNewItem({ ...newItem, type: e.target.value })} /> <span className="text-sm">60kg to 100kg</span>*/}
                  {/*    </label>*/}
                  {/*    <label className="flex  items-center gap-2 mt-2">*/}
                  {/*      <input type="radio" className="radio radio-sm" name="weight" value="100kg to 150kg" onChange={(e) => setNewItem({ ...newItem, type: e.target.value })} /> <span className="text-sm">100kg to 150kg</span>*/}
                  {/*    </label>*/}
                  {/*    <label className="flex  items-center gap-2 mt-2">*/}
                  {/*      <input type="radio" className="radio radio-sm" name="weight" value="More than 150kg" onChange={(e) => setNewItem({ ...newItem, type: e.target.value })} /> <span className="text-sm">More than 150kg</span>*/}
                  {/*    </label>*/}
                  {/*  </fieldset>*/}

                  {/*  /!* REAR WALLS *!/*/}
                  {/*  <fieldset className="mt-3">*/}
                  {/*    <legend className="text-sm font-semibold">Rear Walls</legend>*/}
                  {/*    <label className="flex items-center gap-2 mt-2">*/}
                  {/*      <input type="radio" className="radio radio-sm" name="rear-walls" value="nailed rear wall" onChange={(e) => setNewItem({ ...newItem, type: e.target.value })} /> <span className="text-sm">nailed rear wall</span>*/}
                  {/*    </label>*/}
                  {/*    <label className="flex  items-center gap-2 mt-2">*/}
                  {/*      <input type="radio" className="radio radio-sm" name="rear-walls" value="non-nailed rear wall" onChange={(e) => setNewItem({ ...newItem, type: e.target.value })} /> <span className="text-sm">non-nailed rear wall</span>*/}
                  {/*    </label>*/}
                  {/*  </fieldset>*/}

                  {/*  /!* DOORS *!/*/}
                  {/*  <fieldset className="mt-3">*/}
                  {/*    <legend className="text-sm font-semibold">Doors</legend>*/}
                  {/*    <label className="flex items-center gap-2 mt-2">*/}
                  {/*      <input type="radio" className="radio radio-sm" name="doors" value="Normal doors" onChange={(e) => setNewItem({ ...newItem, type: e.target.value })} /> <span className="text-sm">Normal doors</span>*/}
                  {/*    </label>*/}
                  {/*    <label className="flex  items-center gap-2 mt-2">*/}
                  {/*      <input type="radio" className="radio radio-sm" name="doors" value="Sliding doors" onChange={(e) => setNewItem({ ...newItem, type: e.target.value })} /> <span className="text-sm">Sliding doors</span>*/}
                  {/*    </label>*/}
                  {/*  </fieldset>*/}

                  {/*</div>*/}


                  <div className="flex items-center gap-1 justify-between">
                    <button className="btn btn-outline mt-4" onClick={() => setShowPopup("product")}>Back</button>
                    <button type="button" className="btn btn-neutral mt-4" onClick={addItem}>Confirm</button>
                  </div>
                </div>
              </dialog>
            )}
          </div>

        </div>
      </div>


    </AuthenticatedLayout>
  );
}
